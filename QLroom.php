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
        width: auto !important;      /* Chiều rộng tự động */
        margin: 1.75rem auto;        /* Căn giữa modal */
        }

        .modal-content {
            overflow: auto;              /* Cho phép cuộn nếu nội dung tràn ra ngoài */
        }
    </style>
</head>
<body>
    <div id="notification"></div>
    <div class="table">
        <div class="table_header">
            <p>DANH SÁCH PHÒNG</p>
        </div>
        <form method="POST" action="" enctype="multipart/form-data" onsubmit="return confirmDelete();">
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
            </div>
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" /></th>
                        <th>Hình ảnh</th>
                        <th>Tên loại phòng</th>
                        <th>Giường</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $hname = "localhost";
                    $uname = "root";
                    $pass = '';
                    $db = "qlhotel";
                    $conn = mysqli_connect($hname, $uname, $pass, $db);
                    mysqli_query($conn, "SET NAMES 'utf8'");

                    // Biến thông báo
                    $notificationMessage = "";

                    // Xử lý thêm hoặc sửa dữ liệu
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['save'])) {
                            // Lấy dữ liệu từ form
                            $lp_id = $_POST['id'];
                            $lp_Ten = $_POST['txtten'];
                            $lp_Giuong = $_POST['txtgg'];
                            $lp_Gia = floatval($_POST['txtgia']); // Chuyển đổi sang float hoặc decimal nếu cần
                            $lp_DienTich = $_POST['txtdt'];
                            $lp_SucChua = $_POST['txtsc'];
                            $lp_Mota = $_POST['txtmota'];
                            $lp_dichvu = $_POST['txtdv'];
                            $lp_anh = $_POST['txtanh'];
                            $lp_MaTT = $_POST['MaTTP'];

                        if (isset($_POST['edit-id']) && !empty($_POST['edit-id'])) {
                            $edit_id = $_POST['edit-id'];
                            $stmt = $conn->prepare("UPDATE rooms SET name=?, bed_type=?, price=?, area=?, capacity=?, description=?, services=?, image=?, MaTTP=? WHERE id=?");
                                if($stmt){
                                    $stmt->bind_param("ssdsisssii", $lp_Ten, $lp_Giuong, $lp_Gia, $lp_DienTich, $lp_SucChua, $lp_Mota, $lp_dichvu, $lp_anh, $lp_MaTT, $edit_id);

                                if ($stmt->execute()) {
                                    $notificationMessage = "Cập nhật thành công!";
                                    echo "<script>window.location.href = window.location.href;</script>";
                                    exit; 
                                } else {
                                    $notificationMessage = "Lỗi cập nhật: " . $stmt->error;
                                }

                                $stmt->close();

                                }
                            } else { // Thêm mới
                                $stmt = $conn->prepare("INSERT INTO rooms (id, name, bed_type, price, area, capacity, description, services, image, MaTTP) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                if($stmt){
                                $stmt->bind_param("issdsisssi", $lp_id, $lp_Ten, $lp_Giuong, $lp_Gia, $lp_DienTich, $lp_SucChua, $lp_Mota, $lp_dichvu, $lp_anh, $lp_MaTT);


                                if ($stmt->execute()) {
                                    $notificationMessage = "Thêm mới thành công!";
                                    echo "<script>window.location.href = window.location.href;</script>";
                                    exit; 
                                } else {
                                    $notificationMessage = "Lỗi thêm mới: " . $stmt->error;
                                }

                                $stmt->close();
                            }}


                        } else if (isset($_POST["delete_ids"])) {
                    // Xóa dữ liệu
                            $ids = $_POST["delete_ids"];
                            $stmt_delete = $conn->prepare("DELETE FROM rooms WHERE id = ?");
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
                    // Hiển thị danh sách loại phòng
                    $sql = 'SELECT * FROM rooms';
                    $data = $conn->query($sql);
                    while ($row = $data->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><input type="checkbox" name="delete_ids[]" value="<?php echo $row["id"]; ?>" /></td>
                            <td><img src="<?php echo $row["image"]; ?>" alt="Hình ảnh" style="width: 50px;"></td>
                            <td><?php echo $row["name"]; ?></td>
                            <td><?php echo $row["bed_type"]; ?></td>
                            <td><?php echo number_format($row["price"], 0, ',', '.'); ?> VNĐ</td>
                            <td>
                                <?php
                                // Lấy tên trạng thái từ MaTT
                                $status_query = "SELECT TentrangthaiP FROM statusp WHERE MaTTP = '".$row['MaTTP']."'";
                                $status_result = $conn->query($status_query);
                                $status_row = $status_result->fetch_assoc();
                                echo $status_row['TentrangthaiP'];
                                ?>
                            </td>
                            <td>
                            <button type="button" class="btn btn-primary edit-btn" data-toggle="modal" data-target="#roomModal"
                                data-id="<?php echo $row['id']; ?>"
                                data-name="<?php echo $row['name']; ?>"
                                data-bed_type="<?php echo $row['bed_type']; ?>"
                                data-price="<?php echo $row['price']; ?>"
                                data-area="<?php echo $row['area']; ?>"
                                data-capacity="<?php echo $row['capacity']; ?>"
                                data-description="<?php echo $row['description']; ?>"
                                data-services="<?php echo $row['services']; ?>"
                                data-image="<?php echo $row['image']; ?>"
                                data-matt="<?php echo $row['MaTTP']; ?>"
                                data-action="view">Chi tiết</button>


                            <button type="button" class="btn btn-success edit-btn" data-toggle="modal" data-target="#roomModal"
                                data-id="<?php echo $row['id']; ?>"
                                data-name="<?php echo $row['name']; ?>"
                                data-bed_type="<?php echo $row['bed_type']; ?>"
                                data-price="<?php echo $row['price']; ?>"
                                data-area="<?php echo $row['area']; ?>"
                                data-capacity="<?php echo $row['capacity']; ?>"
                                data-description="<?php echo $row['description']; ?>"
                                data-services="<?php echo $row['services']; ?>"
                                data-image="<?php echo $row['image']; ?>"
                                data-matt="<?php echo $row['MaTTP']; ?>"
                                data-action="edit">Sửa</button>

                        </td>
                    </tr>
                    <?php
                    }

?>
<!-- Modal -->
<div id="roomModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modal-title">Chi tiết phòng</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
            <form id="roomForm" method="POST" action="">
                <input type="hidden" name="edit-id" id="edit-id">
                <div class="row">
                    <div class="col-md-6">
                        <img id="room-image" src="" alt="" class="img-fluid rounded shadow-sm">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtten">Mã phòng</label>
                            <input type="text" name="id" class="form-control" id="id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtten">Tên phòng</label>
                            <input type="text" name="txtten" class="form-control" id="txtten" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtgg">Loại giường</label>
                            <input type="text" name="txtgg" class="form-control" id="txtgg" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtgia">Giá phòng</label>
                            <input type="text" name="txtgia" class="form-control" id="txtgia" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtdt">Diện tích</label>
                            <input type="text" name="txtdt" class="form-control" id="txtdt" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtsc">Sức chứa</label>
                            <input type="text" name="txtsc" class="form-control" id="txtsc" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtmota">Mô tả</label>
                            <textarea class="form-control" name="txtmota" id="txtmota" rows="3" readonly></textarea>
                        </div>
                        <div class="form-group">
                            <label for="txtdv">Dịch vụ</label>
                            <input type="text" name="txtdv" class="form-control" id="txtdv" readonly>
                        </div>
                        <div class="form-group">
                            <label for="txtanh">Hình ảnh</label>
                            <input type="text" name="txtanh" id="txtanh" class="form-control-file" readonly>
                        </div>
                        <div class="form-group">
                            <label for="MaTT">Trạng thái</label>
                            <select name="MaTTP" id="MaTTP" class="form-control" disabled>
                                <option value="">--Chọn trạng thái--</option>
                                <?php
                                $status_query = "SELECT * FROM statusp";
                                $status_data = $conn->query($status_query);
                                while ($status_row = $status_data->fetch_assoc()) {
                                    echo "<option value='" . $status_row['MaTTP'] . "'>" . $status_row['TentrangthaiP'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary" name="save" id="saveBtn" style="display:none">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
//js code
$(document).ready(function() {
$('#roomModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    if(!button.hasClass('edit-btn')){
            return;
        }
    var action = button.data('action');

    $('#modal-title').text(action === 'edit' ? 'Sửa loại phòng' : 'Chi tiết phòng');

    if (action === 'view') {
        // Chế độ xem chi tiết: vô hiệu hóa tất cả các input và ẩn nút "Lưu"
        $('#roomForm input, #roomForm textarea, #roomForm select').prop('readonly', true);
        $('#MaTTP').prop('disabled', true); // Vô hiệu hóa select
        $('#saveBtn').hide();
    } else {
        // Chế độ sửa: cho phép nhập liệu và hiển thị nút "Lưu"
        $('#roomForm input, #roomForm textarea').prop('readonly', false);
        $('#MaTTP').prop('disabled', false); // Cho phép select
        $('#saveBtn').show();
    }
    var id = button.data('id');
    var name = button.data('name');
    var bed_type = button.data('bed_type');
    var price = button.data('price');
    var area = button.data('area');
    var capacity = button.data('capacity');
    var description = button.data('description');
    var services = button.data('services');
    var image = button.data('image');
    var matt = button.data('matt');

    $('#edit-id').val(id);
    $('#id').val(id).prop('readonly', true);;
    $('#txtten').val(name).prop('readonly', false);
    $('#txtgg').val(bed_type).prop('readonly', false);;
    $('#txtgia').val(price).prop('readonly', false);;
    $('#txtdt').val(area).prop('readonly', false);;
    $('#txtsc').val(capacity).prop('readonly', false);;
    $('#txtmota').val(description).prop('readonly', false);;
    $('#txtdv').val(services).prop('readonly', false);;
    $('#txtanh').val(image).prop('readonly', false);;
    $('#MaTTP').val(matt).prop('disabled', false);
    $('#room-image').attr('src', image);
    $('#room-image').attr('alt', name);
});
// Xử lý sự kiện cho nút "Thêm"
        showAddForm = () => {
                $('#modal-title').text('Thêm loại phòng');
                $('#edit-id').val('');
                $('#id').val('').prop('readonly', false); // Cho phép nhập liệu mã phòng khi thêm mới
                $('#txtten').val('');
                $('#txtgg').val('');
                $('#txtgia').val('');
                $('#txtdt').val('');
                $('#txtsc').val('');
                $('#txtmota').val('');
                $('#txtdv').val('');
                $('#txtanh').val('');
                $('#MaTTP').val('').prop('disabled', false);
                $('#room-image').attr('src', ''); // Xóa ảnh cũ
                $('#room-image').attr('alt', '');

                $('#roomModal').modal('show'); // Sửa id modal thành #roomModal
                $('#saveBtn').show();// Hiển thị nút lưu khi thêm mới
            }
});
//js code
</script>

</body>
</html>
