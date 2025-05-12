<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy ID phòng từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id) {
    // Truy vấn CSDL với Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows > 0) {  
        $room = $result->fetch_assoc();
        // Kiểm tra xem người dùng đã đăng nhập chưa
                $logged_in = isset($_SESSION['MaKH']);
                $MaKH = $logged_in ? $_SESSION['MaKH'] : null;

                // Kiểm tra xem người dùng đã đặt phòng này và đã thanh toán chưa
                $check_booking_sql = "SELECT * FROM bookings WHERE id = ? AND MaKH = ? AND MaTTDDP = 3";
                $check_booking_stmt = $conn->prepare($check_booking_sql);
                $check_booking_stmt->bind_param("ii", $id, $MaKH);
                $check_booking_stmt->execute();
                $check_booking_result = $check_booking_stmt->get_result();
                $has_booked_and_paid = $check_booking_result->num_rows > 0;



                // Xử lý đánh giá
                if (isset($_POST['submit_review']) && $has_booked_and_paid) {
                    $rating = intval($_POST['rating']);
                    $review_text = htmlspecialchars($_POST['review_text']);

                    // Lưu đánh giá vào CSDL
                    $insert_review_sql = "INSERT INTO reviews (id, MaKH, rating, review_text) VALUES (?, ?, ?, ?)";
                    $insert_review_stmt = $conn->prepare($insert_review_sql);
                    $insert_review_stmt->bind_param("iiss", $id, $MaKH, $rating, $review_text);
                    if ($insert_review_stmt->execute()) {
                        echo "<script>alert('Đánh giá của bạn đã được gửi.');</script>";
                    } else {
                        echo "<script>alert('Lỗi khi gửi đánh giá.');</script>";
                    }
                    $insert_review_stmt->close();
                }

                 // Lấy tất cả đánh giá của phòng kèm thông tin khách hàng
                    $reviews_sql = "SELECT r.*, c.TenKH, c.image AS customer_image 
                    FROM reviews r
                    INNER JOIN customers c ON r.MaKH = c.MaKH
                    WHERE r.id = ?";
                        $reviews_stmt = $conn->prepare($reviews_sql);
                        $reviews_stmt->bind_param("i", $id);
                        $reviews_stmt->execute();
                        $reviews_result = $reviews_stmt->get_result();
                        $reviews = $reviews_result->fetch_all(MYSQLI_ASSOC);


        // Xử lý đặt phòng khi người dùng submit
        if (isset($_POST['submit_booking'])) {
            if (isset($_SESSION['MaKH'])) {
                $MaKH = $_SESSION['MaKH']; 
                // Nhận giá trị từ form đặt phòng, sử dụng hàm htmlspecialchars để tránh XSS
                $ten_khach_hang = htmlspecialchars(trim($_POST['name']));
                $so_dien_thoai = htmlspecialchars(trim($_POST['phone']));
                $dia_chi = htmlspecialchars(trim($_POST['address']));
                $ngay_den = date("Y-m-d", strtotime($_POST['checkin']));
                $ngay_di = date("Y-m-d", strtotime($_POST['checkout']));
                $id = intval($_POST['id']); // Chuyển sang số nguyên
                $tong_tien = intval($_POST['total_price']); // Chuyển sang số nguyên
                $cccd = htmlspecialchars(trim($_POST['cccd']));
                // Kiểm tra ngày đến và ngày đi
                $ngay_den_timestamp = strtotime($ngay_den);
                $ngay_di_timestamp = strtotime($ngay_di);

                if ($ngay_di_timestamp <= $ngay_den_timestamp) {
                    echo "<script>alert('Ngày đi phải sau ngày đến.');</script>";
                } else {
                    try {
                        // Bắt đầu giao dịch
                        $conn->begin_transaction();

                        // Chèn thông tin vào bảng bookings (sử dụng prepared statement)
                        $stmt_booking = $conn->prepare("INSERT INTO bookings (id, MaKH, NgayDen, NgayDi, TongTien, MaTTDDP) VALUES (?, ?, ?, ?, ?, ?)");
                        if ($stmt_booking === false) {
                            throw new Exception("Lỗi prepare: " . $conn->error);
                        }

                        $maTTDDP = 1; // Gán giá trị cho MaTTDDP vào một biến
                        $stmt_booking->bind_param("iiissi", $id, $MaKH, $ngay_den, $ngay_di, $tong_tien, $maTTDDP); // Sửa chỗ này

                        if (!$stmt_booking->execute()) {
                            throw new Exception("Lỗi execute: " . $stmt_booking->error);
                        }

                        $conn->commit();
                        echo "<script>alert('Đặt phòng thành công!'); window.location.href='index.php?page=rooms';</script>";
                        exit(); // Dừng thực thi PHP sau khi chuyển hướng
                    } catch (Exception $e) {
                        $conn->rollback();
                        echo "<script>alert('Đặt phòng thất bại: " . $e->getMessage() . "');</script>";
                    }
                }
            } else {
                $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI']; // Lưu URL hiện tại
                echo "<script>alert('Bạn cần đăng nhập để đặt phòng.');window.location.href='pages/login.php';</script>";
                exit();
            }
        }
        // Tính toán số sao trung bình
            $totalRating = 0;
            $reviewCount = count($reviews);
            if ($reviewCount > 0) {
                foreach ($reviews as $review) {
                    $totalRating += $review['rating'];
                }
                $averageRating = round($totalRating / $reviewCount); 
            } else {
                $averageRating = 0; 
            }
        ?>
        <!-- Breadcrumb Section Begin -->
        <div class="breadcrumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-text">
                            <h2>Phòng của De L'amour</h2>
                            <div class="bt-option">
                                <a href="./index.php">Trang chủ</a>
                                <span><?php echo htmlspecialchars($room['name']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Section End -->

        <!-- Room Details Section Begin -->
        <section class="room-details-section spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="room-details-item">
                            <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                            <div class="rd-text">
                                <div class="rd-title">
                                    <h3><?php echo htmlspecialchars($room['name']); ?></h3>
                                    <div class="rdt-right">
                                        <div class="rating">
                                            <!-- Hiển thị số sao dựa trên đánh giá -->
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="icon_star <?php echo ($i <= $averageRating) ? '' : '-empty'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <h2><?php echo number_format($room['price']); ?><span>VNĐ/Đêm</span></h2>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="r-o">Diện tích:</td>
                                            <td><?php echo htmlspecialchars($room['area']); ?> m²</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Sức chứa:</td>
                                            <td><?php echo htmlspecialchars($room['capacity']); ?> người</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Giường:</td>
                                            <td><?php echo htmlspecialchars($room['bed_type']); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Dịch vụ:</td>
                                            <td><?php echo htmlspecialchars($room['services']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p class="f-para"><?php echo htmlspecialchars($room['description']); ?></p>
                            </div>
                        </div>
                    <div class="rd-reviews">
                        <h4>Đánh giá</h4>
                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="ri-pic">
                            <img src="<?php echo $review['customer_image']; ?>" alt="Avatar">
                            </div>
                            <div class="ri-text">
                            <span><?php echo $review['date_review']; ?></span>
                                <div class="rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="icon_star <?php echo ($i <= $review['rating']) ? '' : '-empty'; ?>"></i> 
                                <?php endfor; ?>
                                </div>
                                <h5><?php echo $review['TenKH']; ?></h5>
                                <p><?php echo $review['review_text']; ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                        <?php if ($has_booked_and_paid): ?>
                            <div class="review-add">
                                <h4>Thêm đánh giá của bạn về phòng và dịch vụ của chúng tôi</h4>
                                <form method="post" class="ra-form">
                                    <div class="rating-input">
                                        <p>Cho điểm đánh giá của bạn từ 1 đến 5 sao</p>
                                        <input type="number" class="star" name="rating" min="1" max="5" placeholder="Số sao (1-5)">                                    </div>    
                                    <br>
                                    <textarea name="review_text" placeholder="Nhập đánh giá của bạn" required></textarea>
                                    <button type="submit" name="submit_review">Gửi đánh giá</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="room-booking">
                            <h3>Đơn đặt phòng</h3>
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                                <input type="hidden" id="total_price_input" name="total_price" value="0">
                               
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên khách hàng</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cccd" class="form-label">CCCD</label>
                                    <input type="text" name="cccd" id="cccd" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" id="phone" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" name="address" id="address" class="form-control" required>
                                </div>
                                <div class="check-date">
                                    <label for="checkin" class="form-label">Ngày đến</label>
                                    <input type="date" name="checkin" id="checkin" class="form-control " required onchange="calculateTotal()">
                                </div>
                                <div class="check-date">
                                    <label for="checkout" class="form-label">Ngày đi</label>
                                    <input type="date" name="checkout" id="checkout" class="form-control" required onchange="calculateTotal()">
                                </div>
                                <div class="mb-3" id="total_price_section" style="display: none;">
                                    <label class="form-label" id="total_price_label"></label>
                                </div>
                                <button type="submit" name="submit_booking" id="submit_booking" disabled>Đặt phòng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Room Details Section End -->
        <?php include 'searchform.php'; ?>

        <script>
        function calculateTotal() {
            var checkin = document.getElementById('checkin').value;
            var checkout = document.getElementById('checkout').value;

            var pricePerNight = <?php echo $room['price']; ?>; // Giá phòng lấy từ CSDL

            if (checkin && checkout) {
                var startDate = new Date(checkin);
                var endDate = new Date(checkout);
                var timeDiff = endDate - startDate;
                var days = Math.ceil(timeDiff / (1000 * 3600 * 24)); // Làm tròn lên để tính cả ngày cuối cùng

                if (days > 0) {
                    var totalPrice = pricePerNight * days;

                    // Hiển thị tổng tiền
                    var totalPriceText = "Tổng tiền đặt phòng của bạn hết " + totalPrice.toLocaleString() + " VND";
                    document.getElementById('total_price_label').innerText = totalPriceText;

                    // Hiển thị ô tổng tiền
                    document.getElementById('total_price_section').style.display = 'block';

                    // Cập nhật giá trị tổng tiền vào trường ẩn
                    document.getElementById('total_price_input').value = totalPrice;

                    // Kích hoạt nút "Đặt phòng"
                    document.getElementById('submit_booking').disabled = false;
                } else {
                    // Ẩn ô tổng tiền và vô hiệu hóa nút nếu ngày không hợp lệ
                    document.getElementById('total_price_section').style.display = 'none';
                    document.getElementById('submit_booking').disabled = true;

                    alert("Ngày đi phải sau ngày đến.");
                }
            } else {
                document.getElementById('total_price_section').style.display = 'none';
                document.getElementById('submit_booking').disabled = true;
            }
        }
        </script>
        <?php
    } else {
        echo "<script>alert('Không tìm thấy phòng!'); window.location.href = 'index.php';</script>";
    }
}
?>
<!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>