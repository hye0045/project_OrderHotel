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

$MaKH = $_SESSION['MaKH'];

// Truy vấn tất cả đơn đặt phòng của khách hàng dựa trên MaKH
$sql = "SELECT b.*, r.name AS room_name, s.TentrangthaiDDP AS status_name
        FROM bookings b
        INNER JOIN rooms r ON b.id = r.id
        INNER JOIN statusddp s ON b.MaTTDDP = s.MaTTDDP
        WHERE b.MaKH = ?
        ORDER BY b.MaDDP DESC"; 

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare: " . $conn->error);
}
$stmt->bind_param("i", $MaKH);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Lỗi get_result: " . $stmt->error);
}

$bookings = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
<title>Đơn đặt phòng của tôi</title>
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
.button-exit{
    position: absolute;
    top: 10px; 
    right: 10px; 
    background-color: #ff6347; 
    color: white; 
    padding: 10px 15px; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer;
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
        <h2>Đơn đặt phòng của tôi</h2>
        <button class="button-exit" onclick="window.location.href='http://localhost/De%20Lamour%20Hotel/index.php?page=home';">Thoát</button>
        <?php if (count($bookings) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Mã đơn đặt phòng</th>
                        <th>Tên phòng</th>
                        <th>Ngày đến</th>
                        <th>Ngày đi</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['MaDDP']; ?></td>
                            <td><?php echo $booking['room_name']; ?></td>
                            <td><?php echo $booking['NgayDen']; ?></td>
                            <td><?php echo $booking['NgayDi']; ?></td>
                            <td><?php echo number_format($booking['TongTien']); ?> VND</td>
                            <td><?php echo $booking['status_name']; ?></td>
                            <td>
                                <a href="track_booking.php?id=<?php echo $booking['MaDDP']; ?>">Chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Bạn chưa có đơn đặt phòng nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>