<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="type.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  
    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'navigation.php'; ?>
                <main class="p-3">
                    <div class="container-fluid">
                    <?php
                        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; 
                    switch ($page) {
                        case 'dashboard':
                        include 'dashboard.php'; 
                    break;
                        case 'qlroom':
                        include 'QLroom.php'; 
                    break;
                        case 'rooms_admin':
                        include 'rooms_admin.php'; 
                    break;
                        case 'rooms_page':
                        include 'rooms_page.php'; 
                    break;
                        case 'customer':
                        include 'customer.php'; 
                    break;
                       
                    default:
                        include '404.php'; 
                    break;
            }
 
        ?>
    </div>
</main>
        </div>
    </div>
    <script src="js/1.js"></script>
    </body>
</html>