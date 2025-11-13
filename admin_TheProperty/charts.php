<?php
include '../admin_TheProperty/include/db_connection.php';
session_start();

//Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int) $_POST['delete_id'];
    $delete_sql = "DELETE FROM properties WHERE id = $delete_id";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('Property deleted successfully!'); window.location.href='charts.php';</script>";
    } else {
        echo "<script>alert('Error deleting property.');</script>";
    }
}

//Search
$search = trim($_GET['search'] ?? '');
$searchId = null; 

if ($search !== '') {
    // detect PROP prefix like PROP008
    if (stripos($search, 'PROP') === 0) {
        $num = substr($search, 4);
        if (is_numeric($num)) {
            $searchId = (int)$num;
        }
    }

    $sql = "
        SELECT * FROM properties 
        WHERE (
            property_title LIKE ? 
            OR client_name LIKE ? 
            OR client_mobile LIKE ? 
            " . ($searchId !== null ? " OR id = ?" : "") . "
        )
        AND status <> 'Pending'
        AND user_status = 'active'
        ORDER BY id DESC
    ";

    $stmt = $conn->prepare($sql);
    $like = "%$search%";

    if ($searchId !== null) {
        $stmt->bind_param("sssi", $like, $like, $like, $searchId);
    } else {
        $stmt->bind_param("sss", $like, $like, $like);
    }

    $stmt->execute();
    $result = $stmt->get_result();
  } else {
    $sql = "SELECT * FROM properties 
            WHERE status <> 'Pending'
            AND user_status = 'active'
            ORDER BY id DESC";
    $result = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Charts - Kaiadmin Bootstrap 5 Admin Dashboard</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="./assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />
    <!-- Fonts and icons -->
    <script src="./assets/js/plugin/webfont/webfont.min.js"></script>
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
          urls: ["./assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/plugins.min.css" />
    <link rel="stylesheet" href="./assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="./assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
        <?php include './include/side_nav.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
       <?php include './include/nav.php'; ?>

        <div class="container">
          <div class="page-inner">
            <h3 class="fw-bold mb-3">Property Listings Details</h3>
           <div class="row">
              <!-- Search Bar -->
               <section class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                  <h4 class="fw-bold text-primary mb-2 mb-md-0">Property Listings</h4>
                  <input 
                  type="text"
                  id="searchInput"
                  class="form-control w-50"
                  placeholder="🔍 Search by Property ID, Title, or Client Name..."
                  value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                  onkeyup="if(event.key === 'Enter'){ window.location.href='?search=' + this.value; }"
                  >
                </div>
                <div class="container mt-4">
                  <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    if ($result && $result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        //property code
                        $propertyCode = "PROP" . str_pad($row['id'], 3, "0", STR_PAD_LEFT);
                        //Image handling
                        $serverImageDir = $_SERVER['DOCUMENT_ROOT'] . '/TheProperty/admin_TheProperty/include/images/';
                        $browserImageDir = '/TheProperty/admin_TheProperty/include/images/';
                        $defaultImage = '/TheProperty/assets/img/default-property.png';

                        // Extract first image
                        $imagesField = trim($row['property_images'] ?? '');
                        $images = [];

                        if (!empty($imagesField)) {
                          $decoded = json_decode($imagesField, true);
                          if (is_array($decoded)) {
                            $images = $decoded;
                          } else {
                            $images = array_map('trim', explode(',', $imagesField));
                          }
                        }
                        
                        $imagePath = $defaultImage;
                        if (!empty($images)) {
                          $firstImage = basename($images[0]);
                          $fullServerPath = $serverImageDir . $firstImage;

                          if (file_exists($fullServerPath)) {
                            $imagePath = $browserImageDir . $firstImage;
                          }
                        }
                        ?>
                        <div class="col property-card">
                          <div class="card shadow-sm border-0 rounded-4 h-100 d-flex flex-column">
                            <img src="<?php echo htmlspecialchars($imagePath); ?>"
                            class="card-img-top rounded-top-4"
                            alt="Property Image"
                            style="height:200px; object-fit:cover; border-radius: 10px;">
                            <div class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 rounded-end">
                              <?php echo htmlspecialchars($propertyCode); ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                              <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['property_title']); ?></h5>
                              <p class="mb-1">
                                <?php echo htmlspecialchars($row['listing_type']); ?> •
                                <?php echo htmlspecialchars($row['property_type']); ?> •
                                <?php echo htmlspecialchars($row['property_size']); ?> sqft •
                                <?php 
                                $statusClass = ($row['status'] == 'Accepted') ? 'text-success' : 
                                (($row['status'] == 'Rejected') ? 'text-danger' : 'text-warning'); 
                                ?>
                                <span class="<?php echo $statusClass; ?> fw-semibold">
                                  <?php echo htmlspecialchars(ucfirst($row['status'] ?? 'pending')); ?>
                                </span>
                              </p>
                              <p class="mb-1"><strong>Owner:</strong> <?php echo htmlspecialchars($row['client_name']); ?></p>
                              <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($row['client_mobile']); ?></p>
                              <p class="mb-3"><strong>Address:</strong> <?php echo htmlspecialchars($row['property_address']); ?></p>
                              <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="mt-auto">
                                <input type="hidden" name="delete_id" value="<?php echo (int)$row['id']; ?>">
                                <button class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1"><i class="bi bi-trash-fill"></i> Delete</button>
                              </form>
                            </div>
                          </div>
                        </div>
                        <?php
                        }
                      } else {
                        echo "<p class='text-muted'>No properties found.</p>";
                      }
                      ?>
                      </div>
                    </div>
                    <!--pagination-->
                    <nav>
                      <ul class="pagination justify-content-center mt-4" id="pagination"></ul>
                    </nav>
                    <!--length-->
                    <script>
                    const cards = document.querySelectorAll('.property-card');
                    const cardsPerPage = 3;
                    let currentPage = 1;

                    function showPage(page) {
                      const totalPages = Math.ceil(cards.length / cardsPerPage);
                      cards.forEach((card, index) => {
                        card.style.display =
                        index >= (page - 1) * cardsPerPage && index < page * cardsPerPage
                        ? ''
                        : 'none';
                      });

                      const pagination = document.getElementById('pagination');
                      pagination.innerHTML = '';

                      // Previous
                      const prev = document.createElement('li');
                      prev.className = 'page-item' + (page === 1 ? ' disabled' : '');
                      prev.innerHTML = `<a class="page-link" href="#">Previous</a>`;
                      prev.addEventListener('click', () => {
                        if (currentPage > 1) {
                          currentPage--;
                          showPage(currentPage);
                        }
                      });
                      pagination.appendChild(prev);

                      // Page Numbers
                      for (let i = 1; i <= totalPages; i++) {
                        const li = document.createElement('li');
                        li.className = 'page-item' + (i === page ? ' active' : '');
                        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        li.addEventListener('click', () => {
                          currentPage = i;
                          showPage(currentPage);
                        });
                        pagination.appendChild(li);
                      }

                      // Next
                      const next = document.createElement('li');
                      next.className = 'page-item' + (page === totalPages ? ' disabled' : '');
                      next.innerHTML = `<a class="page-link" href="#">Next</a>`;
                      next.addEventListener('click', () => {
                        if (currentPage < totalPages) {
                          currentPage++;
                          showPage(currentPage);
                        }
                      });
                      pagination.appendChild(next);
                    }

                    // Initialize
                    showPage(currentPage);

                    //filter
                    showPage(currentPage);
                    const searchInput = document.getElementById('searchInput');
                    searchInput.addEventListener('input', function() {
                      const filter = this.value.toLowerCase();
                      cards.forEach(card => {
                        const id = card.dataset.id.toLowerCase();
                        card.style.display = id.includes(filter) ? 'block' : 'none';
                      });
                    });
                    </script>

                    <?php include './include/footer.php'; ?>
                  </div>

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>
    <!-- Chart JS -->
    <script src="./assets/js/plugin/chart.js/chart.min.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="./assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="./assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="./assets/js/setting-demo2.js"></script>
    <script>
      var lineChart = document.getElementById("lineChart").getContext("2d"),
        barChart = document.getElementById("barChart").getContext("2d"),
        pieChart = document.getElementById("pieChart").getContext("2d"),
        doughnutChart = document
          .getElementById("doughnutChart")
          .getContext("2d"),
        radarChart = document.getElementById("radarChart").getContext("2d"),
        bubbleChart = document.getElementById("bubbleChart").getContext("2d"),
        multipleLineChart = document
          .getElementById("multipleLineChart")
          .getContext("2d"),
        multipleBarChart = document
          .getElementById("multipleBarChart")
          .getContext("2d"),
        htmlLegendsChart = document
          .getElementById("htmlLegendsChart")
          .getContext("2d");

      var myLineChart = new Chart(lineChart, {
        type: "line",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Active Users",
              borderColor: "#1d7af3",
              pointBorderColor: "#FFF",
              pointBackgroundColor: "#1d7af3",
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 1,
              pointRadius: 4,
              backgroundColor: "transparent",
              fill: true,
              borderWidth: 2,
              data: [
                542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 900,
              ],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
            labels: {
              padding: 10,
              fontColor: "#1d7af3",
            },
          },
          tooltips: {
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10,
          },
          layout: {
            padding: { left: 15, right: 15, top: 15, bottom: 15 },
          },
        },
      });

      var myBarChart = new Chart(barChart, {
        type: "bar",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Sales",
              backgroundColor: "rgb(23, 125, 255)",
              borderColor: "rgb(23, 125, 255)",
              data: [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
        },
      });

      var myPieChart = new Chart(pieChart, {
        type: "pie",
        data: {
          datasets: [
            {
              data: [50, 35, 15],
              backgroundColor: ["#1d7af3", "#f3545d", "#fdaf4b"],
              borderWidth: 0,
            },
          ],
          labels: ["New Visitors", "Subscribers", "Active Users"],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
            labels: {
              fontColor: "rgb(154, 154, 154)",
              fontSize: 11,
              usePointStyle: true,
              padding: 20,
            },
          },
          pieceLabel: {
            render: "percentage",
            fontColor: "white",
            fontSize: 14,
          },
          tooltips: false,
          layout: {
            padding: {
              left: 20,
              right: 20,
              top: 20,
              bottom: 20,
            },
          },
        },
      });

      var myDoughnutChart = new Chart(doughnutChart, {
        type: "doughnut",
        data: {
          datasets: [
            {
              data: [10, 20, 30],
              backgroundColor: ["#f3545d", "#fdaf4b", "#1d7af3"],
            },
          ],

          labels: ["Red", "Yellow", "Blue"],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
          },
          layout: {
            padding: {
              left: 20,
              right: 20,
              top: 20,
              bottom: 20,
            },
          },
        },
      });

      var myRadarChart = new Chart(radarChart, {
        type: "radar",
        data: {
          labels: ["Running", "Swimming", "Eating", "Cycling", "Jumping"],
          datasets: [
            {
              data: [20, 10, 30, 2, 30],
              borderColor: "#1d7af3",
              backgroundColor: "rgba(29, 122, 243, 0.25)",
              pointBackgroundColor: "#1d7af3",
              pointHoverRadius: 4,
              pointRadius: 3,
              label: "Team 1",
            },
            {
              data: [10, 20, 15, 30, 22],
              borderColor: "#716aca",
              backgroundColor: "rgba(113, 106, 202, 0.25)",
              pointBackgroundColor: "#716aca",
              pointHoverRadius: 4,
              pointRadius: 3,
              label: "Team 2",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
          },
        },
      });

      var myBubbleChart = new Chart(bubbleChart, {
        type: "bubble",
        data: {
          datasets: [
            {
              label: "Car",
              data: [
                { x: 25, y: 17, r: 25 },
                { x: 30, y: 25, r: 28 },
                { x: 35, y: 30, r: 8 },
              ],
              backgroundColor: "#716aca",
            },
            {
              label: "Motorcycles",
              data: [
                { x: 10, y: 17, r: 20 },
                { x: 30, y: 10, r: 7 },
                { x: 35, y: 20, r: 10 },
              ],
              backgroundColor: "#1d7af3",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
            xAxes: [
              {
                ticks: {
                  beginAtZero: true,
                },
              },
            ],
          },
        },
      });

      var myMultipleLineChart = new Chart(multipleLineChart, {
        type: "line",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Python",
              borderColor: "#1d7af3",
              pointBorderColor: "#FFF",
              pointBackgroundColor: "#1d7af3",
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 1,
              pointRadius: 4,
              backgroundColor: "transparent",
              fill: true,
              borderWidth: 2,
              data: [30, 45, 45, 68, 69, 90, 100, 158, 177, 200, 245, 256],
            },
            {
              label: "PHP",
              borderColor: "#59d05d",
              pointBorderColor: "#FFF",
              pointBackgroundColor: "#59d05d",
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 1,
              pointRadius: 4,
              backgroundColor: "transparent",
              fill: true,
              borderWidth: 2,
              data: [10, 20, 55, 75, 80, 48, 59, 55, 23, 107, 60, 87],
            },
            {
              label: "Ruby",
              borderColor: "#f3545d",
              pointBorderColor: "#FFF",
              pointBackgroundColor: "#f3545d",
              pointBorderWidth: 2,
              pointHoverRadius: 4,
              pointHoverBorderWidth: 1,
              pointRadius: 4,
              backgroundColor: "transparent",
              fill: true,
              borderWidth: 2,
              data: [10, 30, 58, 79, 90, 105, 117, 160, 185, 210, 185, 194],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "top",
          },
          tooltips: {
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10,
          },
          layout: {
            padding: { left: 15, right: 15, top: 15, bottom: 15 },
          },
        },
      });

      var myMultipleBarChart = new Chart(multipleBarChart, {
        type: "bar",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "First time visitors",
              backgroundColor: "#59d05d",
              borderColor: "#59d05d",
              data: [95, 100, 112, 101, 144, 159, 178, 156, 188, 190, 210, 245],
            },
            {
              label: "Visitors",
              backgroundColor: "#fdaf4b",
              borderColor: "#fdaf4b",
              data: [
                145, 256, 244, 233, 210, 279, 287, 253, 287, 299, 312, 356,
              ],
            },
            {
              label: "Pageview",
              backgroundColor: "#177dff",
              borderColor: "#177dff",
              data: [
                185, 279, 273, 287, 234, 312, 322, 286, 301, 320, 346, 399,
              ],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            position: "bottom",
          },
          title: {
            display: true,
            text: "Traffic Stats",
          },
          tooltips: {
            mode: "index",
            intersect: false,
          },
          responsive: true,
          scales: {
            xAxes: [
              {
                stacked: true,
              },
            ],
            yAxes: [
              {
                stacked: true,
              },
            ],
          },
        },
      });

      // Chart with HTML Legends

      var gradientStroke = htmlLegendsChart.createLinearGradient(
        500,
        0,
        100,
        0
      );
      gradientStroke.addColorStop(0, "#177dff");
      gradientStroke.addColorStop(1, "#80b6f4");

      var gradientFill = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
      gradientFill.addColorStop(0, "rgba(23, 125, 255, 0.7)");
      gradientFill.addColorStop(1, "rgba(128, 182, 244, 0.3)");

      var gradientStroke2 = htmlLegendsChart.createLinearGradient(
        500,
        0,
        100,
        0
      );
      gradientStroke2.addColorStop(0, "#f3545d");
      gradientStroke2.addColorStop(1, "#ff8990");

      var gradientFill2 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
      gradientFill2.addColorStop(0, "rgba(243, 84, 93, 0.7)");
      gradientFill2.addColorStop(1, "rgba(255, 137, 144, 0.3)");

      var gradientStroke3 = htmlLegendsChart.createLinearGradient(
        500,
        0,
        100,
        0
      );
      gradientStroke3.addColorStop(0, "#fdaf4b");
      gradientStroke3.addColorStop(1, "#ffc478");

      var gradientFill3 = htmlLegendsChart.createLinearGradient(500, 0, 100, 0);
      gradientFill3.addColorStop(0, "rgba(253, 175, 75, 0.7)");
      gradientFill3.addColorStop(1, "rgba(255, 196, 120, 0.3)");

      var myHtmlLegendsChart = new Chart(htmlLegendsChart, {
        type: "line",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Subscribers",
              borderColor: gradientStroke2,
              pointBackgroundColor: gradientStroke2,
              pointRadius: 0,
              backgroundColor: gradientFill2,
              legendColor: "#f3545d",
              fill: true,
              borderWidth: 1,
              data: [
                154, 184, 175, 203, 210, 231, 240, 278, 252, 312, 320, 374,
              ],
            },
            {
              label: "New Visitors",
              borderColor: gradientStroke3,
              pointBackgroundColor: gradientStroke3,
              pointRadius: 0,
              backgroundColor: gradientFill3,
              legendColor: "#fdaf4b",
              fill: true,
              borderWidth: 1,
              data: [
                256, 230, 245, 287, 240, 250, 230, 295, 331, 431, 456, 521,
              ],
            },
            {
              label: "Active Users",
              borderColor: gradientStroke,
              pointBackgroundColor: gradientStroke,
              pointRadius: 0,
              backgroundColor: gradientFill,
              legendColor: "#177dff",
              fill: true,
              borderWidth: 1,
              data: [
                542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 900,
              ],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: false,
          },
          tooltips: {
            bodySpacing: 4,
            mode: "nearest",
            intersect: 0,
            position: "nearest",
            xPadding: 10,
            yPadding: 10,
            caretPadding: 10,
          },
          layout: {
            padding: { left: 15, right: 15, top: 15, bottom: 15 },
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  fontColor: "rgba(0,0,0,0.5)",
                  fontStyle: "500",
                  beginAtZero: false,
                  maxTicksLimit: 5,
                  padding: 20,
                },
                gridLines: {
                  drawTicks: false,
                  display: false,
                },
              },
            ],
            xAxes: [
              {
                gridLines: {
                  zeroLineColor: "transparent",
                },
                ticks: {
                  padding: 20,
                  fontColor: "rgba(0,0,0,0.5)",
                  fontStyle: "500",
                },
              },
            ],
          },
          legendCallback: function (chart) {
            var text = [];
            text.push('<ul class="' + chart.id + '-legend html-legend">');
            for (var i = 0; i < chart.data.datasets.length; i++) {
              text.push(
                '<li><span style="background-color:' +
                  chart.data.datasets[i].legendColor +
                  '"></span>'
              );
              if (chart.data.datasets[i].label) {
                text.push(chart.data.datasets[i].label);
              }
              text.push("</li>");
            }
            text.push("</ul>");
            return text.join("");
          },
        },
      });

      var myLegendContainer = document.getElementById("myChartLegend");

      // generate HTML legend
      myLegendContainer.innerHTML = myHtmlLegendsChart.generateLegend();

      // bind onClick event to all LI-tags of the legend
      var legendItems = myLegendContainer.getElementsByTagName("li");
      for (var i = 0; i < legendItems.length; i += 1) {
        legendItems[i].addEventListener("click", legendClickCallback, false);
      } 

      //charts 
      document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById("searchInput");
        if (!searchInput) return;

        let typingTimer;
        searchInput.addEventListener("keyup", function() {
          clearTimeout(typingTimer);
          typingTimer = setTimeout(() => {
            const query = this.value.trim();
            const baseUrl = window.location.pathname; 
            if (query) {
              window.location.href = baseUrl + "?search=" + encodeURIComponent(query);
            } else {
              window.location.href = baseUrl;
            }
          }, 500);
        });

      //search
      searchInput.addEventListener("keypress", e => {
        if (e.key === "Enter") {
          e.preventDefault();
          const query = searchInput.value.trim();
          const baseUrl = window.location.pathname;
          window.location.href = query ? baseUrl + "?search=" + encodeURIComponent(query) : baseUrl;
        }
      });
    });

    document.addEventListener("DOMContentLoaded", () => {
      const input = document.getElementById("searchInput");
      let typingTimer;
      input.addEventListener("keyup", function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
          const query = input.value.trim();
          const baseUrl = window.location.pathname;
          window.location.href = query ? `${baseUrl}?search=${encodeURIComponent(query)}` : baseUrl;
        }, 500);
      });
    });
    </script>
  </body>
</html>
