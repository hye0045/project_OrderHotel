<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");
// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Lấy dữ liệu từ form
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$adults = $_POST['adults'] ?? 1;
$children = $_POST['children'] ?? 0;
$total_guests = $adults + $children;
// Hàm kiểm tra định dạng ngày
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
// Xác thực dữ liệu
if (!validateDate($checkin) || !validateDate($checkout)) {
    die("Định dạng ngày không hợp lệ.");
}
if (strtotime($checkin) >= strtotime($checkout)) {
    die("Ngày nhận phòng phải trước ngày trả phòng.");
}

$sql = "
    SELECT p.id AS room_id, p.name AS room_name, p.capacity, p.price, p.area, p.bed_type, p.services, p.description, p.image
    FROM rooms p
    INNER JOIN statusp rs ON p.MaTTP = rs.MaTTP -- Khóa ngoại: liên kết mã trạng thái phòng
    WHERE rs.TentrangthaiP = 'Trống đã dọn dẹp' -- Chỉ chọn phòng có trạng thái 'trống đã dọn dẹp'
      AND p.capacity >= ?
      AND NOT EXISTS (
          SELECT 1 
          FROM bookings b
          WHERE b.id = p.id
            AND b.MaDDP = '2' -- Loại bỏ nếu đơn đặt ở trạng thái 'đã xác nhận'
            AND (
                (? BETWEEN b.NgayDen AND b.NgayDi) OR
                (? BETWEEN b.NgayDen AND b.NgayDi) OR
                (b.NgayDen BETWEEN ? AND ?) OR
                (b.NgayDi BETWEEN ? AND ?)
            )
      )
";

$stmt = $conn->prepare($sql);
if ($stmt === false) { die('Prepare failed: ' . htmlspecialchars($conn->error)); }
$stmt->bind_param("issssss", $total_guests, $checkin, $checkout, $checkin, $checkout, $checkin, $checkout);
$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách phòng trống
$rooms = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!-- Breadcrumb Section Begin -->
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <h2>Kết quả tìm kiếm phòng</h2>
                    <div class="bt-option">
                        <a href="index.php?page=home">Trang chủ</a>
                        <span>Phòng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Rooms Section Begin -->
    <section class="rooms-section spad">
        <div class="container">
            <div class="row">
            <?php if (!empty($rooms)): ?>
    <div class="row">
        <?php foreach ($rooms as $room): ?>
            <div class="col-lg-4 col-md-6">
                <div class="room-item">
                    <img src="<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['room_name']) ?>"> <div class="ri-text">
                        <h4><?= htmlspecialchars($room['room_name']) ?></h4>
                        <h3><?= number_format($room['price']) ?><span>/Đêm</span></h3>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="r-o">Diện tích:</td>
                                    <td><?= htmlspecialchars($room['area']) ?> m²</td>
                                </tr>
                                <tr>
                                    <td class="r-o">Sức chứa:</td>
                                    <td><?= htmlspecialchars($room['capacity']) ?> người</td>
                                </tr>
                                <tr>
                                    <td class="r-o">Giường:</td>
                                    <td><?= htmlspecialchars($room['bed_type']) ?></td>
                                </tr>
                                <tr>
                                    <td class="r-o">Dịch vụ:</td>
                                    <td><?= htmlspecialchars($room['services']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="index.php?page=room-details&id=<?= $room['room_id'] ?>" class="primary-btn"
                           data-room-id="<?= $room['room_id'] ?>"
                           data-room-name="<?= htmlspecialchars($room['room_name']) ?>"
                           data-room-price="<?= $room['price'] ?>"
                           data-room-image="<?= htmlspecialchars($room['image']) ?>"
                           data-room-description="<?= htmlspecialchars($room['description']) ?>"
                           data-room-area="<?= htmlspecialchars($room['area']) ?>"
                           data-room-capacity="<?= $room['capacity'] ?>"
                           data-room-bed-type="<?= htmlspecialchars($room['bed_type']) ?>"
                           data-room-services="<?= htmlspecialchars($room['services']) ?>"
                           onclick="storeRoomDetails(this); return false;">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
        <div class="alert alert-warning">Không có phòng trống phù hợp với yêu cầu.</div>
    <?php endif; ?>
</div>