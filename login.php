<?php
 session_start();//khởi tạo session để lưu trữ dữ liệu
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Xử lý form đăng nhập
$error = ""; // Biến lưu trữ thông báo lỗi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Truy vấn dữ liệu người dùng từ cơ sở dữ liệu
    $sql = "SELECT MaKH, username, password FROM customers WHERE username = '"  . $username . "'";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {  // Kiểm tra lỗi prepare
      die("Lỗi prepare: " . $conn->error);}
 //   $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; // Mật khẩu đã được băm từ CSDL
        // Xác thực mật khẩu (sử dụng hàm password_verify)
       
         // So sánh mật khẩu bằng password_verify 
         if (password_verify($password, $hashed_password)) { 
            // Đăng nhập thành công
            $_SESSION["MaKH"] = $row["MaKH"];
            $_SESSION["username"] = $username; // Lưu tên người dùng vào session

             // Chuyển hướng sau khi đăng nhập 
            if (isset($_SESSION['redirect_url'])) {
              $redirect_url = $_SESSION['redirect_url'];
              unset($_SESSION['redirect_url']); // Xóa URL sau khi sử dụng
              header("Location: $redirect_url");
              exit();
            } else {
            header("Location: http://localhost/De%20Lamour%20Hotel/index.php?page=home");
            exit();
            }
        } else {
            $error = "Mật khẩu không đúng.";
        }
    } else {
        $error = "Tên người dùng không tồn tại.";
    }
    $stmt->close();
}
$conn->close();
?>
<head>
    <title>Đăng nhập</title>
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
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="col-lg-12">
                    <div class="login-text">
                        <h2>Đăng Nhập</h2>
                        <p>Vui lòng nhập thông tin tài khoản của bạn để đăng nhập.</p>
                        <div>
                        <input type="text" name="username" placeholder="Tên người dùng" required>
                        </div>
                        <input type="password" name="password" placeholder="Mật khẩu" required>
                    </div>
                        <button type="submit">Đăng Nhập</button>
                    </div>
                </form>
            </div>
            <div class="image-section">
            <h3>Hello, Friend!</h3>
            <p>Enter your personal details and start journey with us</p>
        </div>
    </div>
</body>
