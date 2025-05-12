<?php
session_start();
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "qlhotel");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaKH'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem thông tin đặt phòng.'); window.location.href = 'pages/login.php';</script>";
    exit();
}

// Lấy MaDDP từ URL
if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // Truy vấn thông tin chi tiết đơn đặt phòng 
    $sql = "SELECT b.*, r.name AS room_name, r.price AS room_price, c.TenKH AS customer_name, c.SĐT AS customer_phone, c.DiaChi AS customer_address, s.TentrangthaiDDP AS status_name
            FROM bookings b
            INNER JOIN rooms r ON b.id = r.id
            INNER JOIN customers c ON b.MaKH = c.MaKH
            INNER JOIN statusddp s ON b.MaTTDDP = s.MaTTDDP
            WHERE b.MaDDP = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Lỗi prepare: " . $conn->error);
    }
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        die("Lỗi get_result: " . $stmt->error);
    }

    if ($result->num_rows > 0) {
        $booking_details = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy thông tin đơn đặt phòng.";
        exit();
    }

        // Xử lý hủy đơn
        if (isset($_GET['cancel']) && $booking_details['MaTTDDP'] == 1) { 
            $stmt = $conn->prepare("UPDATE bookings SET MaTTDDP = 4 WHERE MaDDP = ?");
            $stmt->bind_param("i", $booking_id);
            if ($stmt->execute()) {
                echo "<script>alert('Hủy đơn đặt phòng thành công!'); window.location.href = 'track.php';</script>";
                exit();
            } else {
                echo "<script>alert('Hủy đơn đặt phòng thất bại!');</script>";
            }
        }

        // Xử lý cập nhật thông tin đặt phòng
        if (isset($_POST['update_booking'])  && $booking_details['MaTTDDP'] == 1) {
        $new_checkin = $_POST['new_checkin'];
        $new_checkout = $_POST['new_checkout'];

        // Kiểm tra ngày
        $days = (strtotime($new_checkout) - strtotime($new_checkin)) / (60 * 60 * 24);
        if ($days <= 0) {
            echo "<script>alert('Ngày đi phải sau ngày đến.');</script>";
        } else {
            // Cập nhật ngày đến và ngày đi
            $stmt = $conn->prepare("UPDATE bookings SET Ngay Den = ?, NgayDi = ? WHERE MaDDP = ? AND MaTTDDP = 1");
            $stmt->bind_param("ssi", $new_checkin, $new_checkout, $booking_id);

            if ($stmt->execute()) {
                // Tính toán lại tổng tiền (nếu cần)
                $stmt_price = $conn->prepare("SELECT r.price FROM rooms r INNER JOIN bookings b ON r.id = b.id WHERE b.MaDDP = ?");
                $stmt_price->bind_param("i", $booking_id);
                $stmt_price->execute();
                $result_price = $stmt_price->get_result();

                if ($result_price->num_rows > 0) {
                    $row_price = $result_price->fetch_assoc();
                    $price = $row_price['price'];
                    $new_total = $price * $days;

                    $stmt_update_price = $conn->prepare("UPDATE bookings SET TongTien = ? WHERE MaDDP = ?");
                    $stmt_update_price->bind_param("di", $new_total, $booking_id);
                    $stmt_update_price->execute();
                }
                echo "<script>alert('Cập nhật thông tin đặt phòng thành công!'); window.location.href = 'track_booking.php';</script>"; // Redirect after successful update
            } else {
                echo "<script>alert('Cập nhật thông tin đặt phòng thất bại.');</script>";
            }
        }
        }
    } else {
    echo "Không có MaDDP.";
    exit();
}
?>

<html>
<head>
    <title>Chi tiết đơn đặt phòng</title>
    <style>
body {
    font-family: Arial, Helvetica, sans-serif , sans-serif;
    margin: 0;
    background-image: url('https://static.vecteezy.com/system/resources/previews/006/849/253/original/abstract-background-with-soft-gradient-color-and-dynamic-shadow-on-background-background-for-wallpaper-eps-10-free-vector.jpg');
    background-size: cover;
    color: #fff; /* Màu chữ trắng */
    /* hoặc thêm thuộc tính sau nếu muốn có hiệu ứng thị sai */
    background-attachment: fixed; 
}

.container {
    width: 90%; /* Giảm chiều rộng để có khoảng trống hai bên */
    max-width: 1200px; /* Giới hạn chiều rộng tối đa */
    margin: 30px auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Đổ bóng nhẹ */
    background-color: rgba(0, 0, 0, 0.6); /* Form bán trong suốt */
    backdrop-filter: blur(5px); /* Hiệu ứng mờ cho form */
    border-radius: 10px;
}

h2, h3 {
    text-align: left;
    color: #fff;
}

table {
    width: 50%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    padding: 10px;
    border: 0px solid #ddd;
    text-align: left;
}

/* CSS cho actions */
.actions {
    display: flex;
}

.actions a, .actions button {
    margin-right: 10px;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold; /* Làm chữ đậm hơn */
    color: #fff; /* Màu chữ trắng cho nút */
    border: none;
}

.cancel-btn { background-color: #dc3545; }
.edit-btn { background-color: #007bff; }

.cancel-btn:disabled, .edit-btn:disabled {
    background-color: #999;
    cursor: not-allowed;
}
.edit-form {
    margin-top: 20px; /* Khoảng cách với bảng chi tiết */
}

.edit-form label {
    display: block;
    margin-bottom: 5px;
}

.edit-form input[type="date"] {
    width: 100%;
    padding: 8px;
    width: 100%;
    height: 50px;
    border: 1px solid #ebebeb;
    border-radius: 2px;
    font-size: 16px;
    color:#19191a;
    text-transform: uppercase;
    font-weight: 500;
    padding-left: 20px;

}
.notification {
    margin-top: 10px;
    padding: 10px;
    background-color: #001f3f;
    border-radius: 4px;
}

/* Responsive - Sắp xếp theo cột khi màn hình nhỏ */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }

    .details-container, .edit-container {
        flex: none; /* Không co giãn nữa */
        width: 100%;
    }
}
    </style>
</head>
<body>
<div class="container">
        <h2>Chi tiết đơn đặt phòng</h2>
        <table>
            <tbody>
            <tr>
                <th>Mã đơn đặt phòng:</th>
                <td><?php echo htmlspecialchars($booking_details['MaDDP']); ?></td>
            </tr>
            <tr>
                <th>Tên phòng:</th>
                <td><?php echo htmlspecialchars($booking_details['room_name']); ?></td>
            </tr>
            <tr>
                <th>Giá phòng / đêm:</th>
                <td><?php echo number_format($booking_details['room_price']); ?> VND</td>
            </tr>
            <tr>
                <th>Tên khách hàng:</th>
                <td><?php echo htmlspecialchars($booking_details['customer_name']); ?></td>
            </tr>
            <tr>
                <th>Số điện thoại:</th>
                <td><?php echo htmlspecialchars($booking_details['customer_phone']); ?></td>
            </tr>
            <tr>
                <th>Địa chỉ:</th>
                <td><?php echo htmlspecialchars($booking_details['customer_address']); ?></td>
            </tr>
            <tr>
                <th>Ngày đến:</th>
                <td><?php echo htmlspecialchars($booking_details['NgayDen']); ?></td>
            </tr>
            <tr>
                <th>Ngày đi:</th>
                <td><?php echo htmlspecialchars($booking_details['NgayDi']); ?></td>
            </tr>
            <tr>
                <th>Tổng tiền:</th>
                <td><?php echo number_format($booking_details['TongTien']); ?> VND</td>
            </tr>
            <tr>
                <th>Trạng thái:</th>
                <td><?php echo htmlspecialchars($booking_details['status_name']); ?></td>
            </tr>
            <?php if ($booking_details['MaTTDDP'] == 1): ?>
                <tr>
                    <th>Tác vụ:</th>
                    <td class="actions">
                        <a href="?id=<?php echo $booking_id; ?>&cancel=1" class="cancel-btn" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn đặt phòng này?');">Hủy</a>
                        <button class="edit-btn" onclick="showEditForm('<?php echo $booking_details['NgayDen']; ?>', '<?php echo $booking_details['NgayDi']; ?>')">Sửa</button>
                        <a href="track.php">Thoát</a>
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>

        <?php if ($booking_details['MaTTDDP'] == 1): ?>
        <div class="edit-container" id="editForm" style="display:none;">
            <h3>Chỉnh sửa thông tin đặt phòng</h3>
            <form method="POST" action="">
                <label for="new_checkin">Ngày đến mới:</label>
                <input type="date" name="new_checkin" id="new_checkin" value="<?php echo htmlspecialchars($booking_details['NgayDen']); ?>" onchange="showUpdateConfirmation()"><br>

                <label for="new_checkout">Ngày đi mới:</label>
                <input type="date" name="new_checkout" id="new_checkout" value="<?php echo htmlspecialchars($booking_details['NgayDi']); ?>" onchange="showUpdateConfirmation()"><br>
                <div class="notification" id="updateNotification" style="display:none;">
                    <p>Ngày thay đổi sẽ làm thay đổi tổng tiền. Bạn có chắc chắn muốn cập nhật?</p>
                    <button type="submit" name="update_booking">Cập nhật</button>
                </div>
            </form>
        </div>
        <?php endif; ?>

</div>

<script>
            function showEditForm(checkin, checkout) {
                document.getElementById('new_checkin').value = checkin;
                document.getElementById('new_checkout').value = checkout;
                document.getElementById('editForm').style.display = 'block';
            }

            function showUpdateConfirmation() {
                var checkin = document.getElementById('new_checkin').value;
                var checkout = document.getElementById('new_checkout').value;
                var notification = document.getElementById('updateNotification');

                // Kiểm tra ngày đi phải sau ngày đến
                if (checkin && checkout && new Date(checkout) > new Date(checkin)) {
                    notification.style.display = 'block';
                } else {
                    notification.style.display = 'none';
                }
            }
        </script>
</body>
</html>