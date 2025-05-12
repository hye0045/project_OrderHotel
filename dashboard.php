    <main class="p-3">
        <div class="container-fluid">
            <div class="mb-3 text-center">
                <div class="col-md-12 main-content">
                            <!--Muốn chèn các nội dung về bảng thì bắt đầu chèn ở đây nhé-->
        <h2 style="size:60px; text-align:center;padding:20px ">Thông tin trong ngày</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card" style="background-color: #ff6c6c;">
                <h5 class="text-white">64352 <small>clicks last 30 days</small></h5>
                <p class="text-white"><small>50280 unique | 13639 repeated</small></p>
                <i class="fas fa-mouse-pointer card-icon" style="color: #fff;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card" style="background-color: #28a745;">
                <h5 class="text-white">$ 2108.65 <small>commissions last 30 days</small></h5>
                <p class="text-white"><small>$ 7693.17 paid | $ 6414.76 pending</small></p>
                <i class="fas fa-dollar-sign card-icon" style="color: #fff;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card" style="background-color: #ffc107;">
                <h5 class="text-white">103560 <small>impressions last 30 days</small></h5>
                <p class="text-white"><small>31995 unique | 71565 repeated</small></p>
                <i class="fas fa-chart-line card-icon" style="color: #fff;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card" style="background-color: #17a2b8;">
                <h5 class="text-white">$ 0.00 <small>refunds last 30 days</small></h5>
                <p class="text-white"><small>$ 0.00 paid | $ 0.00 pending</small></p>
                <i class="fas fa-undo card-icon" style="color: #fff;"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="dashboard-card">
                <canvas id="salesChart"></canvas>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="dashboard-card text-center" style="border-left: 5px solid #28a745;">
                        <h5 class="text-success">$ 761.20</h5>
                        <p class="text-muted">Approved</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center" style="border-left: 5px solid #17a2b8;">
                        <h5 class="text-info">$ 9973.85</h5>
                        <p class="text-muted">Paid</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center" style="border-left: 5px solid #ffc107;">
                        <h5 class="text-warning">$ 18176.95</h5>
                        <p class="text-muted">Pending</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card text-center" style="border-left: 5px solid #dc3545;">
                        <h5 class="text-danger">$ 0.00</h5>
                        <p class="text-muted">Refunds</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card">
                <h5>Pending Tasks</h5>
                <div class="row">
                    <div class="col-6">
                        <p>Affiliates</p>
                        <p>DirectLink Urls</p>
                    </div>
                    <div class="col-6 text-right">
                        <p>4</p>
                        <p>0</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <p>Commissions</p>
                        <p>Unsent emails</p>
                    </div>
                    <div class="col-6 text-right">
                        <p>7420</p>
                        <p>94</p>
                    </div>
                </div>
            </div>
            <div class="news-box">
                <h5>NEWS</h5>
                <p>Ultimate list of Affiliate Programs</p>
                <p>Looking to join more affiliate programs? Visit our our blog to see the <strong>ultimate list of affiliate programs.</strong> Currently 70+ affiliate programs are listed in the directory. Full link for the </p>
                <p>directory: <a href="#">http://www.postaffiliatepro.com/blog/ultimate-list-of-affiliate-programs-part-12/</a></p>
                <p class="text-muted"><small>08/26/2014</small></p>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sample data for the chart (replace with your actual data)
var data = {
labels: ['11/17', '11/18', '11/19', '11/20', '11/21', '11/22', '11/23', '11/24'],
datasets: [{
    label: 'Approved',
    data: [120, 59, 80, 81, 56, 55, 40, 100],
    borderColor: '#28a745',
    fill: false
}, {
    label: 'Paid',
    data: [50, 48, 70, 110, 99, 120, 30, 150],
    borderColor: '#17a2b8',
    fill: false
}, {
    label: 'Pending',
    data: [100, 150, 200, 50, 10, 50, 300, 10],
    borderColor: '#ffc107',
    fill: 'origin',
    backgroundColor: 'rgba(255, 193, 7, 0.2)'
}]
};


// Chart configuration
var config = {
type: 'line',
data: data,
options: {
    responsive: true,
    scales: {
        y: {
            beginAtZero: true
        }
    }
}
};


// Create the chart
var salesChart = new Chart(document.getElementById('salesChart'), config);
</script>

                        </div>
                    </div>
                </main>
            </div>
        </div>
       <!-- Cài đặt người dùng -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Cài đặt người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button type="button" class="btn btn-outline-primary btn-block" onclick="window.location.href='login.html'">Cài đặt</button>
                    <button type="button" class="btn btn-outline-primary btn-block" onclick="window.location.href='signup.html'">Thiết lập hệ thống</button>
                    <button type="button" class="btn btn-outline-primary btn-block" onclick="window.location.href='logout.html'">Đăng xuất</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
        <script src="1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>


