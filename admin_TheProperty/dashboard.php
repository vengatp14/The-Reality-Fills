<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
    <style>
      .dashboard-cards {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        gap: 20px;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
      }

      .dashboard-cards .card {
        flex: 1;
        min-width: 200px;
        max-width: 230px;
        white-space: nowrap;
      }
      </style>
    <?php
    include '../admin_TheProperty/include/db_connection.php';
    session_start();

    // Check login session
    if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_TheProperty/index.php");
    exit();
    }

    // Dashboard counters
    $total = $conn->query("SELECT COUNT(*) AS total FROM properties")->fetch_assoc()['total'] ?? 0;
    $rent = $conn->query("SELECT COUNT(*) AS rent FROM properties WHERE LOWER(TRIM(listing_type))='rent'")->fetch_assoc()['rent'] ?? 0;
    $buy = $conn->query("SELECT COUNT(*) AS buy FROM properties WHERE LOWER(TRIM(listing_type))='buy'")->fetch_assoc()['buy'] ?? 0;
    $sell = $conn->query("SELECT COUNT(*) AS sell FROM properties WHERE LOWER(TRIM(listing_type))='sell'")->fetch_assoc()['sell'] ?? 0;
    $lease = $conn->query("SELECT COUNT(*) AS lease FROM properties WHERE LOWER(TRIM(listing_type))='lease'")->fetch_assoc()['lease'] ?? 0;

    
    //Data for chart
    $months = [];
    $rentData = [];
    $buyData = [];
    $sellData = [];
    $leaseData = [];

    for ($m = 1; $m <= 12; $m++) {
      $monthName = date("M", mktime(0, 0, 0, $m, 1));
      $months[] = $monthName;

      $rentCount = $conn->query("SELECT COUNT(*) AS c FROM properties WHERE LOWER(TRIM(listing_type))='rent' AND MONTH(created_at)=$m")->fetch_assoc()['c'] ?? 0;
      $buyCount = $conn->query("SELECT COUNT(*) AS c FROM properties WHERE LOWER(TRIM(listing_type))='buy' AND MONTH(created_at)=$m")->fetch_assoc()['c'] ?? 0;
      $sellCount = $conn->query("SELECT COUNT(*) AS c FROM properties WHERE LOWER(TRIM(listing_type))='sell' AND MONTH(created_at)=$m")->fetch_assoc()['c'] ?? 0;
      $leaseCount = $conn->query("SELECT COUNT(*) AS c FROM properties WHERE LOWER(TRIM(listing_type))='lease' AND MONTH(created_at)=$m")->fetch_assoc()['c'] ?? 0;

      $rentData[] = (int)$rentCount;
      $buyData[] = (int)$buyCount;
      $sellData[] = (int)$sellCount;
      $leaseData[] = (int)$leaseCount;
    }
    ?>

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" /></head>
  <body>
    <div class="wrapper">
      <?php include './include/side_nav.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
       <?php include './include/nav.php'; ?>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 col-sm-6">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Total</p>
                          <h4 class="card-title"><?= $total ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-user-check"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Rent</p>
                          <h4 class="card-title"><?= $rent ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-luggage-cart"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Buy</p>
                          <h4 class="card-title"><?= $buy ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-2 col-sm-6">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="far fa-check-circle"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Sell</p>
                          <h4 class="card-title"><?= $sell ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-2 col-sm-6">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-icon">
                      <div 
                        class="icon-big text-center bubble-shadow-small" style="background-color: orange; color: white; border-radius: 10px;"
                      >
                        <i class="fas fa-file-contract"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">Lease</p>
                      <h4 class="card-title"><?= $lease ?></h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">User Statistics</div>
                    </div>
                  </div>
                  <div class="chart-container" style="min-height: 375px">
                    <canvas id="propertyChart"></canvas>
                  </div>
                    <div id="myChartLegend"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
           <?php include './include/footer.php'; ?>
      </div>

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <!--script src="assets/js/plugin/chart.js/chart.min.js"></script-->

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <!--script src="assets/js/demo.js"></script-->
     <!--$("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      }); -->
      <script>
      //data chart
      const labels = <?= json_encode($months); ?>;
      const rentData = <?= json_encode($rentData); ?>;
      const buyData = <?= json_encode($buyData); ?>;
      const sellData = <?= json_encode($sellData); ?>;
      const leaseData = <?= json_encode($leaseData); ?>;

      console.log("Rent:", rentData);
      console.log("Buy:", buyData);
      console.log("Sell:", sellData);
      console.log("Lease:", leaseData);

      const ctx = document.getElementById("propertyChart").getContext("2d");

      const propertyChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels, 
          datasets: [
          {
            label: "Rent",
            data: rentData,
            borderColor: "#36A3F7",
            backgroundColor: "#36A3F7",
            fill: false,
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 7,
            pointHoverRadius: 7
          },
          {
            label: "Buy",
            data: buyData,
            borderColor: "#31CE36",
            backgroundColor: "#31CE36",
            fill: false,
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 7,
            pointHoverRadius: 7
          },
          {
            label: "Sell",
            data: sellData,
            borderColor: "#6861CE",
            backgroundColor: "#6861CE",
            fill: false,
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 7,
            pointHoverRadius: 7
          },
          {
            label: "Lease",
            data: leaseData,
            borderColor: "#FF9F40",
            backgroundColor: "#FF9F40",
            fill: false,
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 7,
            pointHoverRadius: 7
          }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            datalabels: {
              color: "#444",
              anchor: "end",
              align: "top",
              font: { weight: "bold" },
              formatter: Math.round
            },
            legend: {
              position: "bottom",
              labels: {
                usePointStyle: true,
                pointStyle: "circle",
                padding: 20,
                color: "#444",
                font: { size: 13, weight: "500" },
              },
            },
            tooltip: {
              backgroundColor: "#1f1f1f",
              titleFont: { weight: "bold" },
              bodyFont: { size: 13 },
              cornerRadius: 6,
            },
          },
          scales: {
            x: {
              grid: {
                display: true,
                color: "rgba(200,200,200,0.1)",
              },
              ticks: {
                color: "#666",
                font: { size: 13 },
              },
            },
            y: {
              beginAtZero: true,
              suggestedMax: Math.max(...rentData, ...buyData, ...sellData, ...leaseData) + 1,
              ticks: {
                stepSize: 1, 
                color: "#666",
                font: { size: 13 },
              },
              grid: {
                color: "rgba(200,200,200,0.1)",
              },
            },
          },
        },
        });
</script>
</body>
</html>
