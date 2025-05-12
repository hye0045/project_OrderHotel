<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "qlhotel");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Truy vấn lấy phòng đề xuất (4 phòng ngẫu nhiên)
$sql = "SELECT * FROM rooms ORDER BY RAND() LIMIT 4";
$result = $conn->query($sql);

?>

 <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <div class="hero-text">
                        <h1>Khách sạn De L'amour </h1>
                        <h3>Bắt đầu hành trình của bạn</h3>
                        <p>Mang đến cho khách hàng trải nghiệm khác biệt với thiết kế năng động,
                             không theo khuôn mẫu của khách sạn bãi biển truyền thống 
                              và hướng đến phong cách sống chất lượng với chi phí phải chăng.</p>
                        <a href="#" class="primary-btn">Khám phá</a>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 offset-xl-2 offset-lg-1">
                <!--THEME GIF DDOS-->
                </div>
            </div>
            </div>
        </div>
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="img/hero/hero-1.jpg"></div>
            <div class="hs-item set-bg" data-setbg="img/hero/hero-2.jpg"></div>
            <div class="hs-item set-bg" data-setbg="img/hero/hero-3.jpg"></div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Us Section Begin -->
    <section class="aboutus-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-text">
                        <div class="section-title">
                            <span>Giới thiệu</span>
                            <h2>Khách sạn quốc tế <br />De L'amour</h2>
                        </div>
                        <p class="f-para">DeL'amour.com là websites mà bạn có thể đặt nơi lưu trú trực tuyến.Hằng ngày chúng tôi đón tầm
                            khoảng 100 khách mỗi ngày.
                        </p>
                        <p class="s-para">Vì vậy đến với khách sạn chúng tôi bạn sẽ phục vụ từ phòng đến dịch vụ đều được chăm chút hết mức tốt nhất.
                            Luôn lắng nghe những đề nghị và đánh giá để cải thiệu để làm vừa lòng khách hàng.
                        </p>
                        <a href="#" class="primary-btn about-btn">Hiển thị thêm</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-pic">
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="img/about/about-1.jpg" alt="">
                            </div>
                            <div class="col-sm-6">
                                <img src="img/about/about-2.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us Section End -->

    <!-- Services Section End -->
    <section class="services-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Dịch vụ chúng tôi có </span>
                        <h2>Khám phá ngay</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-036-parking"></i>
                        <h4>Kế hoạch du lịch</h4>
                        <p>Khám Phá Đà Nẵng  
                             Cầu Vàng ẩn hiện sau màn sương sớm.
                            Tượng Cá Chép Hoá Rồng lập loè dưới ánh sáng pháo hoa.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-033-dinner"></i>
                        <h4>Dịch vụ phục vụ tại phòng</h4>
                        <p>Room service có thể được phục vụ 24/7.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-026-bed"></i>
                        <h4>Giường cho em bé</h4>
                        <p>Khách sạn đều cung cấp 
                            giường cũi cho em bé an toàn,êm ái.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-024-towel"></i>
                        <h4>Giặt là</h4>
                        <p>Có sẵn dịch vụ giặt là tại khách sạn,
                            khách hàng có thể đăng ký dịch vụ kèm theo.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-044-clock-1"></i>
                        <h4> Thuê tài xế</h4>
                        <p>Khách hàng có thể thuê tài xế hoặc xe của khách sạn</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-012-cocktail"></i>
                        <h4>Quầy bar&Thức uống</h4>
                        <p>Có hết tất cả các loại thức uống với giá cả phải chăng và đa dạng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Home Room Section Begin -->
    <h2 style="margin-left:30px;padding-bottom: 30px;">Phòng đề xuất:</h2>
    <section class="hp-room-section">
    <div class="container-fluid">
        <div class="hp-room-items">
            <div class="row">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="hp-room-item set-bg" data-setbg="<?php echo $row['image']; ?>">
                                <div class="hr-text">
                                    <h3><?php echo $row['name']; ?></h3>
                                    <h2><?php echo $row['price']; ?><span>VNĐ/Đêm</span></h2>
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td class="r-o">Diện tích:</td>
                                            <td><?php echo $row['area']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Sức chứa:</td>
                                            <td><?php echo $row['capacity']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Giường:</td>
                                            <td><?php echo $row['bed_type']; ?></td>
                                        </tr>
                                        <!--<tr>
                                            <td class="r-o">Dịch vụ:</td>
                                            <td><?php echo $row['services']; ?></td>-->
                                        <!--</tr>-->
                                        </tbody>
                                    </table>
                                    <a href="index.php?page=room-details&id=<?php echo $row['id']; ?>" class="primary-btn">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
$conn->close();
?>
    <!-- Home Room Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Testimonials</span>
                        <h2>What Customers Say?</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="testimonial-slider owl-carousel">
                        <div class="ts-item">
                            <p>After a construction project took longer than expected, my husband, my daughter and I
                                needed a place to stay for a few nights. As a Chicago resident, we know a lot about our
                                city, neighborhood and the types of housing options available and absolutely love our
                                vacation at De L'amour Hotel.</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5> - Alexander Vasquez</h5>
                            </div>
                        </div>
                        <div class="ts-item">
                            <p>After a construction project took longer than expected, my husband, my daughter and I
                                needed a place to stay for a few nights. As a Chicago resident, we know a lot about our
                                city, neighborhood and the types of housing options available and absolutely love our
                                vacation at De L'amour Hotel.</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5> - Alexander Vasquez</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->
    <?php include 'searchform.php'; ?>

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Hotel News</span>
                        <h2>Our Blog & Event</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel Trip</span>
                            <h4><a href="#">Tremblant In Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Camping</span>
                            <h4><a href="#">Choosing A Static Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 15th April, 2024</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Copper Canyon</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 21th April, 2022</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog-item small-size set-bg" data-setbg="img/blog/blog-wide.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Event</span>
                            <h4><a href="#">Trip To Iqaluit In Nunavut A Canadian Arctic City</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 08th April, 2019</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item small-size set-bg" data-setbg="img/blog/blog-10.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Travel</span>
                            <h4><a href="#">Traveling To Barcelona</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> 12th April, 2020</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'searchform.php'; ?>
    <script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/jquery.nice-select.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
    <!-- Blog Section End -->