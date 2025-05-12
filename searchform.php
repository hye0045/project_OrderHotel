<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<style>
.booking-form-fixed {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: fit-content;
    background-color: rgba(255, 255, 255, 0.8);
    padding: 5px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 999;
    border-radius: 10px;
}
.search-form .form-group {
    display: flex;
    align-items: center;
    gap: 10px;
}
.search-form input[type="date"]{
    width: 100px;
    height: 25px;
}
.search-form .quantity-input {
    width: 90px;
    height: 25px;
}
.quantity-input::placeholder {
    color: #bbb;
    font-style: italic;
}
.search-form button {
    height: 30px;
}
.search-form .increment-button {
    width: 20px;
    height: 30px;
    padding: 0;
}
.check-date, .select-option{
    display: flex;
    align-items: center;
    gap:5px;
}
.search-button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #fff; 
    width: auto; 
    padding: 5px 10px; /* Thêm padding cho nút */
    white-space: nowrap; /* Ngăn nút xuống dòng */
    background-color:#87ceeb; /* Màu nền cho nút */
    color: white; /* Màu chữ trắng */
    border: none; /* Bỏ viền */
    border-radius: 5px;
    margin-top: -2px;
}
</style>
</head>
<body>
<div class="booking-form-fixed">
    <form action="index.php?page=search" method="POST" class="search-form">
        <div class="form-group">
        <div class="check-date">
                <label for="checkin">Ngày đến:</label> <input type="date" class="date-input" id="checkin" name="checkin" placeholder="mm/dd/yyyy">
            </div>
            <div class="check-date">
                <label for="checkout">Ngày đi:</label><input type="date" class="date-input" id="checkout" name="checkout" placeholder="mm/dd/yyyy">
            </div>
            <div class="select-option">
                <input type="number" class="quantity-input" id="adults" name="adults" min="0" placeholder="Người lớn">
            </div>
            <div class="select-option">
                <input type="number" class="quantity-input" id="childrens" name="childrens" min="0" placeholder="Trẻ em">
            </div>
            <button type="submit" class="search-button">Tìm kiếm </button>
        </div>
    </form>
</div>

</body>
</html>