<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "qlhotel");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['MaKH'])) {
    echo "<script>alert('Bạn cần đăng nhập để xem trang này.'); window.location.href = 'login.php';</script>";
    exit();
}

$MaKH = $_SESSION['MaKH'];

// Lấy thông tin khách hàng
$sql = "SELECT * FROM customers WHERE MaKH = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $MaKH);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Xử lý chỉnh sửa thông tin
$edit_mode = isset($_GET['edit']) ? true : false;
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    // Gán giá trị từ $_POST nếu có, nếu không thì lấy giá trị hiện tại từ CSDL
    $TenKH = isset($_POST['TenKH']) ? $_POST['TenKH'] : $customer['TenKH'];
    $SDT = isset($_POST['SDT']) ? $_POST['SDT'] : $customer['SĐT'];
    $DiaChi = isset($_POST['DiaChi']) ? $_POST['DiaChi'] : $customer['DiaChi'];
    $CCCD = isset($_POST['CCCD']) ? $_POST['CCCD'] : $customer['CCCD'];
    $username = isset($_POST['username']) ? $_POST['username'] : $customer['username'];
    $image = $customer['image'];
    // Xử lý mật khẩu (chỉ băm nếu người dùng nhập mật khẩu mới)
    $password = isset($_POST['password']) && !empty($_POST['password']) 
        ? password_hash($_POST['password'], PASSWORD_DEFAULT) 
        : $customer['password']; // Giữ nguyên mật khẩu hiện tại nếu không thay đổi

    $update_sql = "UPDATE customers SET TenKH=?, SĐT=?, DiaChi=?, CCCD=?, username=?, password=? ,image=? WHERE MaKH=?"; // Cập nhật câu lệnh SQL
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssi", $TenKH, $SDT, $DiaChi, $CCCD, $username, $password,$image,$MaKH);


    if ($update_stmt->execute()) {
        $success_message = "Cập nhật thông tin thành công.";
        // Cập nhật lại thông tin khách hàng sau khi chỉnh sửa
        $stmt->execute(); // Thực thi lại truy vấn ban đầu
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        $edit_mode = false; // Thoát khỏi chế độ chỉnh sửa
    } else {
        $error_message = "Cập nhật thông tin thất bại." . $update_stmt->error; // Hiển thị lỗi nếu có
    }
    $update_stmt->close();
}

?>

<title>Thông tin tài khoản</title>
<head>
    <style>   
    .manage-info-form label {
    display: block;
    margin-bottom: 5px;
}

.manage-info-form input[type="text"],
.manage-info-form input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Bao gồm padding và border trong width */
}


.manage-info-form button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

/* Thêm class để tránh xung đột */
.manage-info-container {
    width: 80%;
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}
.manage-info-container h2{
    text-align: center;
}

.manage-info-profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 20px;
    display: block;
}

.manage-info-details-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.manage-info-details-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.manage-info-details-list li:last-child {
    border-bottom: none;
}

.manage-info-details-list label {
    font-weight: bold;
    margin-right: 10px;
}

.manage-info-details-list span {
    flex-grow: 1;
}
/* CSS cho nút */
.manage-info-edit-btn, .manage-info-cancel-btn, .manage-info-save-btn{
    background-color: #4CAF50;
    border: none;
    color: white;
    padding: 8px 15px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
}

.manage-info-edit-btn:hover, .manage-info-save-btn:hover{
    background-color: #3e8e41;
}

.manage-info-cancel-btn {
    background-color: #f44336;
}

.manage-info-cancel-btn:hover {
    background-color: #da190b;
}
    </style>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">  
</head>
<body>
<div class="manage-info-container">

        <h2>Thông tin cá nhân</h2>
        <img src="<?php echo isset($customer['image']) && $customer['image'] != '' ? $customer['image'] : 'images/default-image.png'; ?>" alt="image" class="manage-info-profile-image">        
        <ul class="manage-info-details-list">

            <li><label>Tên:</label><span><?php echo $customer['TenKH']; ?></span></li>
            <li><label>Số điện thoại:</label><span><?php echo $customer['SĐT']; ?></span></li>
            <li><label>Địa chỉ:</label><span><?php echo $customer['DiaChi']; ?></span></li>
            <li><label>CCCD:</label><span><?php echo $customer['CCCD']; ?></span></li>
            <li><label>Tên người dùng:</label><span><?php echo $customer['username']; ?></span></li>
        </ul>
        
        <?php if (!$edit_mode): ?>  
            <button type="button" class="btn btn-primary manage-info-edit-btn" data-toggle="modal" data-target="#editModal">Chỉnh sửa</button>  
            <a href="http://localhost/De%20Lamour%20Hotel/index.php?page=home" class="manage-info-cancel-btn">Thoát</a>
        <?php endif; ?>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Chỉnh sửa thông tin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="manage-info-form">
                        <label for="TenKH">Tên:</label>
                            <input type="text" name="TenKH" id="TenKH" value="<?php echo $customer['TenKH']; ?>" required><br>
                            <label for="SDT">Số điện thoại:</label>
                            <input type="text" name="SDT" id="SDT" value="<?php echo $customer['SĐT']; ?>" required><br>

                            <label for="DiaChi">Địa chỉ:</label>
                            <input type="text" name="DiaChi" id="DiaChi" value="<?php echo $customer['DiaChi']; ?>" required><br>

                            <label for="CCCD">CCCD:</label>
                            <input type="text" name="CCCD" id="CCCD" value="<?php echo $customer['CCCD']; ?>" required><br>

                            <label for="username">Tên người dùng:</label>
                            <input type="text" name="username" id="username" value="<?php echo $customer['username']; ?>" required><br>

                            <label for="password">Mật khẩu:</label>
                            <input type="password" name="password" id="password" placeholder="Nhập mật khẩu mới (nếu muốn thay đổi)"><br>
                            
                            <label for="image">Ảnh đại diện:</label>
                            <input type="file" name="image" id="image"><br>

                            <label for="image_url">Hoặc URL ảnh:</label>
                            <input type="text" name="image_url" id="image_url" placeholder="Dán URL ảnh tại đây"><br>
                            <?php if ($success_message): ?>
                                <p style="color: green;"><?php echo $success_message; ?></p>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <p style="color: red;"><?php echo $error_message; ?></p>
                            <?php endif; ?>
                            <button type="submit" name="save" class="manage-info-save-btn">Lưu</button>
                            <button type="button" class="manage-info-cancel-btn" data-dismiss="modal">Hủy</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <?php if ($edit_mode): ?>
        <script>  
            $(document).ready(function(){        
                $('#editModal').modal('show');
            });
        </script>
    <?php endif; ?>

</body>
</html>