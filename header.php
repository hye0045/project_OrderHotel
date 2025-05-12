<?php
// Kiểm tra đăng nhập và hiển thị nút "Đơn đặt phòng của tôi"
$login_display = isset($_SESSION['MaKH']) ? 'block' : 'none'; // Ẩn/hiện nút
$dropdown_display = isset($_SESSION['MaKH']) ? 'none' : 'block'; // Ẩn/hiện dropdown
?>
<header class="header-section header-normal">
        <div class="top-nav">
          <div class="container">
            <div class="row">
              <div class="col-lg-6">
                <ul class="tn-left">
                  <li><i class="fa fa-phone"></i>(+84) 905 880 010</li>
                  <li><i class="fa fa-envelope"></i> DeL'amourHotel@gmail.com</li>
                </ul>
              </div>
              <div class="col-lg-6">
                <div class="tn-right">
                  <div class="top-social">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                  </div>
                  <a href="index.php?page=rooms" class="bk-btn">Đặt ngay</a>
                  <div class="language-option">
                    <img src="https://s.pro.vn/veeV" alt="">
                    <span>VI<i class="fa fa-angle-down"></i></span>
                    <div class="flag-dropdown">
                        <ul>
                            <li><a href="#">EN</a></li>
                        </ul>
                    </div>
                </div>
                  <!-- Nút User -->
                  <div class="user-area">
                  <div class="user-area">
                      <div class="dropdown"> <!-- Bao bọc bằng dropdown -->
                          <a href="#" id="user-icon" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <!-- Thêm data-toggle="dropdown" -->
                              <i class="fa fa-user"></i>
                          </a>
                          <div class="dropdown-menu" aria-labelledby="user-icon">
                              <?php if (isset($_SESSION['MaKH'])): ?>
                                  <a class="dropdown-item" href="pages/track.php">Đơn đặt phòng của tôi</a>
                                  <a class="dropdown-item" href="index.php?page=account_customers">Tài khoản của tôi</a> 
                                  <a class="dropdown-item" href="pages/logout.php">Đăng xuất</a> 
                                  <a class="dropdown-item" href="#">Cài đặt</a> 
                                  <a class="dropdown-item" href='#'>Thiết lập</a>
                              <?php else: ?>
                                  <a class="dropdown-item" href="pages/login.php">Đăng nhập</a> 
                                  <a class="dropdown-item" href="pages/register.php">Đăng ký</a> 
                              <?php endif; ?>
                          </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <!-- END Modal -->
        <div class="menu-item">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="logo">
                            <a href="index.php?page=home">
                            <img src="/De%20Lamour%20Hotel/img/logo.png" alt="De L'amour Hotel">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="nav-menu">
                            <nav class="mainmenu">
                                <ul>
                                    <li><a href="index.php?page=home">Trang chủ</a></li>
                                    <li><a href="index.php?page=rooms">Phòng</a></li>
                                    <li><a href="index.php?page=about-us">Giới thiệu</a></li>
                                    <li><a href="index.php?page=blog">Blog</a></li>
                                    <li><a href="index.php?page=contact">Liên lạc</a></li>
                                </ul>
                            </nav>
                            <div class="nav-right search-switch">
                                <i class="icon_search"></i>
                                <a heft ="search.php" class="search-toggle"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
     <!-- Header End -->
  <!-- Js Plugins -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>