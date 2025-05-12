<?php
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'qlhotel');
mysqli_set_charset($conn, 'utf8');

if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}
 // Lấy danh sách trạng thái
$statusQuery = "SELECT * FROM statusddp";
$statusResult = $conn->query($statusQuery);

// Lấy danh sách phòng
$roomQuery = "SELECT * FROM rooms";
$roomResult = $conn->query($roomQuery);

// Lọc đơn đặt phòng theo trạng thái nếu có
$filterStatus = isset($_POST['filterStatus']) ? $_POST['filterStatus'] : null;
$whereClause = "";
if ($filterStatus) {
    $whereClause = "WHERE dp.MaTTDDP = " . $filterStatus;
}
// Handle form submission for adding or editing booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitBooking'])) {
    $editID = trim($_POST['edit-id']);
    $tenKH = trim($_POST['txtten']);
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $sdt = trim($_POST['txtsdt']);
    $diaChi = trim($_POST['txtdc']);
    $ngayDen = $_POST['check_in'];
    $ngayDi = $_POST['check_out'];
    $maTTDDP = $_POST['MaTTDDP'];
    $tongTien = $_POST['TongTien'];

    if (empty($editID)) {
        // New booking logic
        $stmt_check = $conn->prepare("SELECT COUNT(*) as count, price FROM rooms WHERE id = ?");
        $stmt_check->bind_param("s", $maLP);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] > 0) {
            $giaPhong = $row_check['price']; // Lấy giá phòng

            // Tính tổng tiền dựa trên số ngày
            $days = (strtotime($ngayDi) - strtotime($ngayDen)) / (60 * 60 * 24);
            $tongTien = $giaPhong * $days;

            $conn->begin_transaction();
            try {
                // Insert into tbl_khachhang
                $stmt_khachhang = $conn->prepare("INSERT INTO customers (TenKH, SĐT, DiaChi) VALUES (?, ?, ?)");
                $stmt_khachhang->bind_param("sss", $tenKH, $sdt, $diaChi);
                $stmt_khachhang->execute();
                $maKH = $conn->insert_id;

                // Insert into tbl_dondatphong
                $stmt_booking = $conn->prepare("INSERT INTO bookings (id, MaKH, NgayDen, NgayDi, TongTien, MaTTDDP) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt_booking->bind_param("sissii", $maLP, $maKH, $ngayDen, $ngayDi, $tongTien, $maTTDDP);
                $stmt_booking->execute();

                $conn->commit();
                echo "<script>alert('Đặt phòng thành công!');</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Đặt phòng thất bại: " . $e->getMessage() . "');</script>";
            }
        }
    } else {
        // Cập nhật thông tin khách hàng và trạng thái đơn đặt phòng
if (!empty($editID)) {
    // Chỉ sửa thông tin khách hàng và trạng thái
    $stmt_khachhang = $conn->prepare("UPDATE customers SET TenKH = ?, SĐT = ?, DiaChi = ? WHERE MaKH = (SELECT MaKH FROM bookings WHERE MaDDP = ?)");
    $stmt_khachhang->bind_param("sssi", $tenKH, $sdt, $diaChi, $editID);
    $stmt_khachhang->execute();

    // Cập nhật trạng thái đơn đặt phòng
    $stmt_booking = $conn->prepare("UPDATE bookings SET MaTTDDP = ? WHERE MaDDP = ?");
    $stmt_booking->bind_param("ii", $maTTDDP, $editID);
    $stmt_booking->execute();

    if ($stmt_booking->affected_rows > 0) {
        echo "<script>showNotification('Cập nhật đơn đặt phòng thành công!');</script>";
    } else {
        echo "<script>alert('Cập nhật không thành công hoặc không có thay đổi!');</script>";
    }
}
    }
}

// Xóa đơn đặt phòng
if (isset($_POST["delete_selected"])) {
    if (isset($_POST["delete_ids"])) {
        $ids = $_POST["delete_ids"];
        foreach ($ids as $id) {
            $sql = "DELETE FROM bookings WHERE MaDDP = '$id'";
            $conn->query($sql);
        }
        echo '<script>showNotification("Dữ liệu đã được xóa thành công.")</script>';
    }
}

// Fetch status options
$statusQuery = "SELECT * FROM statusddp";
$statusResult = $conn->query($statusQuery);

// Fetch available room types from database
$roomQuery = "SELECT * FROM rooms";
$roomResult = $conn->query($roomQuery);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .table { width: 100%; }
        .action-buttons { margin: 10px 10px; }
        .table_header { display: flex; background-color: rgb(240, 240, 240); padding-left: 13px; align-items: center; height: 50px; }
        .table_header p { font-size: 1.3rem; font-weight: 400; margin: 0; line-height: 50px; }
        .table-custom thead th { background-color: rgb(240, 240, 240); }
        #notification { position: fixed; top: 20px; right: 20px; background-color: #4CAF50; color: white; padding: 15px; border-radius: 5px; display: none; opacity: 0; transition: opacity 0.5s ease; }
    </style>
</head>
<body>
    <div id="notification"></div>
    <div class="table">
        <div class="table_header">
            <p>DANH SÁCH ĐƠN ĐẶT PHÒNG</p>
        </div>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="d-flex justify-content-between action-buttons mb-3">
                <div>
                    <button type="button" class="btn btn-success btn-custom" onclick="showAddForm()"> + Thêm</button>
                    <button type="submit" class="btn btn-danger btn-custom" name="delete_selected"> x Xóa</button>
                </div>
                <div class="input-group" style="max-width: 500px;">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="bx bx-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
                <!-- Bộ lọc trạng thái -->
                <div class="form-group">
                    <select class="form-control" name="filterStatus" id="filterStatus">
                        <option value="">Tất cả trạng thái</option>
                        <?php while ($row = $statusResult->fetch_assoc()) { ?>
                            <option value="<?= $row['MaTTDDP'] ?>" <?= $filterStatus == $row['MaTTDDP'] ? 'selected' : '' ?>>
                                <?= $row['TentrangthaiDDP'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>Mã đơn đặt phòng</th>
                        <th>Ngày đến</th>
                        <th>Ngày đi</th>
                        <th>Thông tin khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP logic to fetch and display bookings -->
                    <?php
                    // Fetch and display data from tbl_dondatphong
                    $sql = "SELECT dp.*, kh.TenKH, kh.SĐT, kh.DiaChi, tt.TentrangthaiDDP FROM bookings dp
                            INNER JOIN customers kh ON dp.MaKH = kh.MaKH
                            INNER JOIN statusddp tt ON dp.MaTTDDP = tt.MaTTDDP
                            $whereClause";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td><input type='checkbox' name='delete_ids[]' value='{$row["MaDDP"]}' /></td>
                                <td>{$row['MaDDP']}</td>
                                <td>{$row['NgayDen']}</td>
                                <td>{$row['NgayDi']}</td>
                                <td>
                                    <strong>{$row['TenKH']}</strong><br>
                                    {$row['SĐT']}<br>
                                    {$row['DiaChi']}
                                </td>
                                <td>{$row['TongTien']}</td>
                                <td>{$row['TentrangthaiDDP']}</td>
                                <td>
                                    <button type='button' class='btn btn-primary' onclick=\"showForm('{$row['MaDDP']}', '{$row['id']}', '{$row['TenKH']}', '{$row['SĐT']}', '{$row['DiaChi']}', '{$row['NgayDen']}', '{$row['NgayDi']}', '{$row['TongTien']}', '{$row['MaTTDDP']}')\">Sửa</button>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- Modal Form for Adding/Editing Booking -->
<div class="modal" id="bookingModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Thêm đơn đặt phòng</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="edit-id" id="edit-id">
                    <div class="form-group">
                        <label for="txtten">Tên khách hàng:</label>
                        <input type="text" class="form-control" name="txtten" id="txtten" required>
                    </div>
                    <div class="form-group">
                        <label for="txtsdt">Số điện thoại:</label>
                        <input type="text" class="form-control" name="txtsdt" id="txtsdt" required>
                    </div>
                    <div class="form-group">
                        <label for="txtdc">Địa chỉ:</label>
                        <input type="text" class="form-control" name="txtdc" id="txtdc" required>
                    </div>
                    <div class="form-group">
                        <label for="check_in">Ngày đến:</label>
                        <input type="date" class="form-control" name="check_in" id="check_in" readonly>
                    </div>
                    <div class="form-group">
                        <label for="check_out">Ngày đi:</label>
                        <input type="date" class="form-control" name="check_out" id="check_out" readonly>
                    </div>
                    <!-- Remove total price input from edit -->
                    <div class="form-group">
    <label for="MaTTDDP">Trạng thái:</label>
    <select class="form-control" name="MaTTDDP" id="MaTTDDP">
        <?php 
        // Đảm bảo có dữ liệu từ $statusResult trước khi lặp qua
        if ($statusResult) {
            while ($row = $statusResult->fetch_assoc()) {
                echo "<option value='{$row['MaTTDDP']}'>{$row['TentrangthaiDDP']}</option>";
            }
        } else {
            echo "<option value=''>Không có trạng thái</option>";
        }
        ?>
    </select>
</div>
                    <!-- Remove room selection on edit -->
                    <div class="form-group">
                        <label for="id">Loại phòng:</label>
                        <select class="form-control" name="id" id="id" disabled>
                            <?php while ($row = $roomResult->fetch_assoc()) {
                                echo "<option value='{$row['id']}' data-price='{$row['price']}'>{$row['name']}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="TongTien">Tổng tiền:</label>
                        <input type="text" class="form-control" name="TongTien" id="TongTien" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="submitBooking">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        document.getElementById("filterStatus").addEventListener("change", function() {
            this.form.submit();
        });

        function showForm(id = '', roomID = '', name = '', phone = '', address = '', checkIn = '', checkOut = '', total = '', statusID = '') {
    $('#edit-id').val(id);
    $('#txtten').val(name);
    $('#txtsdt').val(phone);
    $('#txtdc').val(address);
    $('#check_in').val(checkIn);
    $('#check_out').val(checkOut);
    $('#TongTien').val(total);
    $('#MaTTDDP').val(statusID);  // Set trạng thái
    $('#id').val(roomID); // Set selected room type

    if (id) {
        // Editing Mode
        $('#modalTitle').text('Sửa đơn đặt phòng');
        $('#id').prop('disabled', true);
        $('#check_in').prop('readonly', true);
        $('#check_out').prop('readonly', true);
        $('#TongTien').prop('readonly', true);
    } else {
        // Adding Mode
        $('#modalTitle').text('Thêm đơn đặt phòng');
        $('#id').prop('disabled', false);
        $('#check_in').prop('readonly', false);
        $('#check_out').prop('readonly', false);
        $('#TongTien').prop('readonly', true); // Still calculated dynamically
    }

    $('#bookingModal').modal('show');
}


function validateForm() {
    const phone = document.getElementById('txtsdt').value;
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;

    if (!/^\d{10,11}$/.test(phone)) {
        alert('Số điện thoại không hợp lệ!');
        return false;
    }

    if (new Date(checkIn) >= new Date(checkOut)) {
        alert('Ngày đi phải sau ngày đến!');
        return false;
    }

    return true;
}
document.getElementById('check_in').addEventListener('change', calculateTotal);
document.getElementById('check_out').addEventListener('change', calculateTotal);
document.getElementById('id').addEventListener('change', calculateTotal);

function calculateTotal() {
    const checkIn = new Date(document.getElementById('check_in').value);
    const checkOut = new Date(document.getElementById('check_out').value);
    const roomPrice = document.querySelector('#id option:checked').dataset.price;

    if (checkIn && checkOut && roomPrice) {
        const days = (checkOut - checkIn) / (1000 * 60 * 60 * 24);
        document.getElementById('TongTien').value = days > 0 ? days * roomPrice : 0;
    }
}
        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.opacity = 1;
            }, 100);
            setTimeout(() => {
                notification.style.opacity = 0;
            }, 3000);
        }

        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xóa các mục đã chọn?");
        }
    </script>
</body>
</html>















