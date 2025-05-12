<?php
// Kết nối cơ sở dữ liệu
$hname = "localhost";
$uname = "root";
$pass = "";
$db = "qlhotel";
$conn = mysqli_connect($hname, $uname, $pass, $db);
mysqli_query($conn, "SET NAMES 'utf8'");

// Biến thông báo
$notificationMessage = "";

// Xử lý thêm hoặc sửa dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Cập nhật thông tin khách hàng
    if (isset($_POST['edit-id']) && !empty($_POST['edit-id'])) {
        $edit_id = $_POST['edit-id'];
        $tenKH = $_POST['txtten'];
        $sdt = $_POST['txtdt'];
        $diachi = $_POST['txtdiachi'];

        $stmt = $conn->prepare("UPDATE customers SET TenKH=?, SĐT=?, DiaChi=? WHERE MaKH=?");
        $stmt->bind_param("sssi", $tenKH, $sdt, $diachi, $edit_id);

        if ($stmt->execute()) {
            $notificationMessage = "Cập nhật thành công!";
        } else {
            $notificationMessage = "Lỗi cập nhật: " . $stmt->error;
        }

        $stmt->close();
    }

    // Xóa khách hàng đã chọn
    if (isset($_POST["delete_ids"])) {
        $ids = $_POST["delete_ids"];
        $stmt_delete = $conn->prepare("DELETE FROM customers WHERE MaKH = ?");
        $stmt_delete->bind_param("i", $id);
        foreach ($ids as $id) {
            $stmt_delete->execute();
        }
        $stmt_delete->close();
        $notificationMessage = "Dữ liệu đã được xóa thành công.";
    }

    // Hiển thị thông báo
    if (!empty($notificationMessage)) {
        echo "<script>showNotification('$notificationMessage');</script>";
    }
}

// Hiển thị danh sách khách hàng
$sql = 'SELECT * FROM customers';
$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .table {
            width: 100%;
        }
        .action-buttons {
            margin: 10px 10px;
        }
        .table_header {
            display: flex;
            background-color: rgb(240, 240, 240);
            padding-left: 13px;
            align-items: center;
            height: 50px;
        }
        .table_header p {
            font-size: 1.3rem;
            font-weight: 400;
            margin: 0;
            line-height: 50px;
        }
        .table-custom thead th {
            background-color: rgb(240, 240, 240);
        }
        #notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .modal-dialog {
            width: auto !important;
            margin: 1.75rem auto;
        }
        .modal-content {
            overflow: auto;
        }
    </style>
</head>
<body>

<div id="notification"></div>
<div class="table">
    <div class="table_header">
        <p>DANH SÁCH KHÁCH HÀNG</p>
    </div>
    <form method="POST" action="" enctype="multipart/form-data" onsubmit="return confirmDelete();">
        <div class="d-flex justify-content-between action-buttons mb-3">
            <div>
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
        </div>
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all" /></th>
                    <th>Tên Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Tác vụ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $data->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" name="delete_ids[]" value="<?php echo $row["MaKH"]; ?>" /></td>
                        <td><?php echo $row["TenKH"]; ?></td>
                        <td><?php echo $row["SĐT"]; ?></td>
                        <td><?php echo $row["DiaChi"]; ?></td>
                        <td>
                            <button type="button" class="btn btn-primary edit-btn" data-toggle="modal" data-target="#roomModal"
                                    data-id="<?php echo $row['MaKH']; ?>"
                                    data-name="<?php echo $row['TenKH']; ?>"
                                    data-phone="<?php echo $row['SĐT']; ?>"
                                    data-address="<?php echo $row['DiaChi']; ?>"
                                    data-action="view">Chi tiết</button>
                            <button type="button" class="btn btn-success edit-btn" data-toggle="modal" data-target="#roomModal"
                                    data-id="<?php echo $row['MaKH']; ?>"
                                    data-name="<?php echo $row['TenKH']; ?>"
                                    data-phone="<?php echo $row['SĐT']; ?>"
                                    data-address="<?php echo $row['DiaChi']; ?>"
                                    data-action="edit">Sửa</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Modal Edit/View -->
<div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Sửa thông tin khách hàng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="roomForm" method="POST">
                    <input type="hidden" name="edit-id" id="edit-id">
                    <div class="form-group">
                        <label for="txtten">Tên khách hàng</label>
                        <input type="text" class="form-control" id="txtten" name="txtten" required>
                    </div>
                    <div class="form-group">
                        <label for="txtdt">Số điện thoại</label>
                        <input type="text" class="form-control" id="txtdt" name="txtdt" required>
                    </div>
                    <div class="form-group">
                        <label for="txtdiachi">Địa chỉ</label>
                        <input type="text" class="form-control" id="txtdiachi" name="txtdiachi" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary" id="saveBtn" form="roomForm">Lưu</button>
            </div>
        </div>
    </div>
</div>

<script>
// Hàm hiển thị thông báo
function showNotification(message) {
    var notification = document.getElementById('notification');
    notification.innerText = message;
    notification.style.display = 'block';
    setTimeout(function () {
        notification.style.opacity = 1;
    }, 100);

    setTimeout(function () {
        notification.style.opacity = 0;
        setTimeout(function () {
            notification.style.display = 'none';
        }, 500);
    }, 3000);
}

// Xử lý modal edit/view
$(document).ready(function() {
    $('#roomModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var action = button.data('action');

        $('#modal-title').text(action === 'edit' ? 'Sửa thông tin khách hàng' : 'Chi tiết khách hàng');

        if (action === 'view') {
            // Chế độ xem chi tiết: vô hiệu hóa tất cả các input và ẩn nút "Lưu"
            $('#roomForm input').prop('readonly', true);
            $('#saveBtn').hide();
        } else {
            // Chế độ sửa: cho phép nhập liệu và hiển thị nút "Lưu"
            $('#roomForm input').prop('readonly', false);
            $('#saveBtn').show();
        }

        var id = button.data('id');
        var name = button.data('name');
        var phone = button.data('phone');
        var address = button.data('address');

        $('#edit-id').val(id);
        $('#txtten').val(name);
        $('#txtdt').val(phone);
        $('#txtdiachi').val(address);
    });
});
</script>

</body>
</html>
