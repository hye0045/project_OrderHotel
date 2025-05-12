<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý form đăng ký
$error = ""; // Biến lưu thông báo lỗi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tenkh = $_POST["tenkh"];
    $sdt = $_POST["sdt"];
    $diachi = $_POST["diachi"];
    $cccd = $_POST["cccd"];
    $username = $_POST["username"];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // biến đổi thành chuỗi để lưu trữ 


    // Kiểm tra xem tên người dùng đã tồn tại chưa
    $check_username_sql = "SELECT * FROM customers WHERE username = ?";
    $check_username_stmt = $conn->prepare($check_username_sql);
    
    if ($check_username_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $check_username_stmt->bind_param("s", $username);
    $check_username_stmt->execute();
    $check_username_result = $check_username_stmt->get_result();

    if ($check_username_result->num_rows > 0) {
        $error = "Tên người dùng đã tồn tại.";
    } else {
        // Thêm dữ liệu vào bảng customers
        $insert_sql = "INSERT INTO customers (TenKH, SĐT, DiaChi, CCCD, username, password) VALUES (?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        
        if ($insert_stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $insert_stmt->bind_param("ssssss", $tenkh, $sdt, $diachi, $cccd, $username, $password); // Mã hóa mật khẩu trước khi lưu trữ

        if ($insert_stmt->execute()) {
            // Đăng ký thành công, chuyển hướng đến trang đăng nhập
            echo "<script>alert('Đăng ký thành công. Vui lòng đăng nhập.'); window.location.href = 'login.php';</script>";
            exit();
        } else {
            $error = "Đăng ký thất bại: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    }
    $check_username_stmt->close();
}

$conn->close();
?>
<head>
    <title>Đăng ký</title>
    <style>
    body {
            font-family: 'Arial', sans-serif; 
            margin: 0;
            background-image: url('https://static.vecteezy.com/system/resources/previews/006/849/253/original/abstract-background-with-soft-gradient-color-and-dynamic-shadow-on-background-background-for-wallpaper-eps-10-free-vector.jpg');
            display: flex;
            background-size: cover;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            border-radius: 10px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Để ẩn phần thừa của ảnh */
        }

        .form-section {
            padding: 40px;
            width: 50%; /* Điều chỉnh độ rộng nếu cần */
            background-color: rgba(255, 255, 255, 0.5); /* Form bán trong suốt */
            backdrop-filter: blur(5px); /* Hiệu ứng mờ cho form */
        }

        .image-section {
            width: 50%; /* Điều chỉnh độ rộng nếu cần */
            background-size: cover;
            background-position: center;
            background-image: url('https://dragonocean.com.vn/uploads/ks1.jpg'); /* Thay đường dẫn ảnh của bạn */
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 0 10px 10px 0;
        }

        h2 {
            font-size: 2em; /* Kích thước chữ tiêu đề */
            margin-bottom: 15px;
            color: #333; 
            align-items: center;
        }
        p {
            font-size: 1em;
            margin-bottom: 20px;
            color: #333; /* Màu chữ mô tả */
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em; /* Kích thước chữ input */
            color: #333; /* Màu chữ trong input */
        }

        button {
            background-color: #126d92;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 40%;
            font-size: 1em; /* Kích thước chữ button */
        }
        button:hover {
            background-color: #87ceeb; /* Background color on hover */ 
            color: black;}
        .image-section h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        .image-section p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .form-section, .image-section {
                width: 100%;
            }

            .image-section {
                border-radius: 0 0 10px 10px; /* Bo tròn góc dưới */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Đăng ký</h2>
            <p>Vui lòng nhập thông tin để đăng ký tài khoản.</p>

            <?php if ($error): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <input type="text" name="tenkh" placeholder="Tên khách hàng" required><br>
                <input type="text" name="sdt" placeholder="Số điện thoại" required><br>
                <input type="text" name="diachi" placeholder="Địa chỉ" required><br>
                <input type="text" name="cccd" placeholder="CCCD" required><br>
                <input type="text" name="username" placeholder="Tên người dùng" required><br>
                <input type="password" name="password" placeholder="Mật khẩu" required><br>
                <button type="submit">Đăng ký</button>
            </form>
        </div>
        <div class="image-section">
            <h3>Hello, Friend!</h3>
            <p>Enter your personal details and start your journey with us</p>
        </div>
    </div>
</body>
</html>
