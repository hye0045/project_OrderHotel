<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn lấy danh sách phòng
$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);
?>

<!-- Breadcrumb Section Begin -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h2>Phòng</h2>
                    <div class="bt-option">
                        <a href="index.php?page=home">Trang chủ</a>
                        <span>Phòng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section End -->

<!-- Rooms Section Begin -->
<section class="rooms-section spad">
    <div class="container">
        <div class="row">
            <?php 
            // Kiểm tra và hiển thị danh sách phòng
            if ($result->num_rows > 0) {
                while($room = $result->fetch_assoc()) { 
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="room-item">
                        <img src="<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                        <div class="ri-text">
                            <h4><?php echo htmlspecialchars($room['name']); ?></h4>
                            <h3><?php echo number_format($room['price']); ?><span>VNĐ/Đêm</span></h3>
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
                            <a href="index.php?page=room-details&id=<?php echo $room['id']; ?>" class="primary-btn"
                               data-room-id="<?php echo $room['id']; ?>"
                               data-room-name="<?php echo htmlspecialchars($room['name']); ?>"
                               data-room-price="<?php echo $room['price']; ?>"
                               data-room-image="<?php echo htmlspecialchars($room['image']); ?>"
                               data-room-description="<?php echo htmlspecialchars($room['description']); ?>"
                               data-room-area="<?php echo htmlspecialchars($room['area']); ?>"
                               data-room-capacity="<?php echo $room['capacity']; ?>"
                               data-room-bed-type="<?php echo htmlspecialchars($room['bed_type']); ?>"
                               data-room-services="<?php echo htmlspecialchars($room['services']); ?>"
                               onclick="storeRoomDetails(this); return false;">Chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p>Không có phòng nào được tìm thấy.</p>";
            }
            ?>
            
            <div class="col-lg-12">
                <div class="room-pagination">
                    <a href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">Tiếp <i class="fa fa-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Rooms Section End -->

<?php
$conn->close(); // Đóng kết nối CSDL
?>
<?php include 'searchform.php'; ?>
