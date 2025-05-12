<?php
session_start();

// Xóa MaKH khỏi session
unset($_SESSION['MaKH']);

// Hủy toàn bộ session (tùy chọn, nếu bạn muốn xóa tất cả dữ liệu session)
// session_destroy();

// Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
header("Location: http://localhost/De%20Lamour%20Hotel/index.php?page=home");
exit();
?>