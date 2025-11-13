<?php
include '../admin_TheProperty/include/db_connection.php';
session_start();

// Assuming user email is stored in session
$user_email = $_SESSION['user'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize and assign inputs
    $full_name = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $user_email = $_SESSION['user'] ?? 'guest';
    $location = $_POST['location'] ?? '';
    $taluk = $_POST['taluk'] ?? '';
    $district = $_POST['district'] ?? '';
    $state = $_POST['state'] ?? '';
    $country = $_POST['country'] ?? '';
    $acre_extent = $_POST['acreExtent'] ?? '';
    $plot_extent = $_POST['plotExtent'] ?? '';
    $road_width = $_POST['roadWidth'] ?? '';
    $zone_classification = $_POST['zoneClassification'] ?? '';
    $dry_land = isset($_POST['dryLand']) ? 1 : 0;
    $wet_land = isset($_POST['wetLand']) ? 1 : 0;
    $development_category = isset($_POST['developmentCategory']) ? json_encode($_POST['developmentCategory']) : '[]';
    $finance_zone = $_POST['financeZone'] ?? '';
    $advance_amount = $_POST['advanceAmount'] ?? 0;
    $ratio = $_POST['ratio'] ?? '';
    $refundable = $_POST['refundable'] ?? 'No';
    $non_refundable = $_POST['nonRefundable'] ?? 'No';
    $user_status = 'Pending';
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO  joint_ventures (
        full_name, email, mobile, user_email, location, taluk, district, state, country,
        acre_extent, plot_extent, road_width, zone_classification,
        dry_land, wet_land, development_category,
        finance_zone, advance_amount, ratio, refundable, non_refundable,
        user_status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    //Check if prepare() failed
    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
    "sssssssssssssssssssssss",
    $full_name, $email, $mobile, $user_email, $location,
    $taluk, $district, $state, $country,
    $acre_extent, $plot_extent, $road_width, $zone_classification,
    $dry_land, $wet_land, $development_category,
    $finance_zone, $advance_amount, $ratio,
    $refundable, $non_refundable, $user_status, $created_at
);

    if ($stmt->execute()) {
        header("Location: ../jvco.php?status=success");
        exit;
    } else {
        die("Insert failed: " . $stmt->error);
    }
}

// Fetch all joint ventures
$result = $conn->query("SELECT * FROM joint_ventures ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Forms - Kaiadmin Bootstrap 5 Admin Dashboard</title>
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
    <style>
        body {
            background-color: #f8f9fa;
            padding: 30px;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .btn-action {
            border-radius: 25px;
            padding: 4px 12px;
            font-size: 14px;
        }
        .status-badge {
            font-size: 13px;
            border-radius: 20px;
            padding: 4px 10px;
        }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
     <?php include './include/side_nav.php'; ?>
      <!-- End Sidebar -->
      <!-- End Sidebar -->

      <div class="main-panel">
        <?php include './include/nav.php'; ?>


        <div class="container">
    <div class="card p-4">
        <h3 class="mb-4 text-center">Joint Venture Requests</h3>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Location</th>
                        <th>Plot Extent</th>
                        <th>Advance Amount</th>
                        <th>User Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['mobile']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['plot_extent']) ?></td>
                            <td><?= htmlspecialchars($row['advance_amount']) ?></td>
                            <td>
                                <?php
                                    $status = strtolower($row['user_status']);
                                    if ($status === 'accepted') {
                                        echo '<span class="badge bg-success status-badge">Accepted</span>';
                                    } elseif ($status === 'rejected') {
                                        echo '<span class="badge bg-danger status-badge">Rejected</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark status-badge">Pending</span>';
                                    }
                                ?>
                            </td>
                            <td>
                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                <?php if (strtolower($row['user_status']) === 'pending'): ?>
                                    <form method="POST" class="d-inline me-1">
                                        <input type="hidden" name="jv_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action_type" value="accept">
                                        <button type="submit" class="btn btn-success btn-sm px-3 btn-action">Accept</button>
                                    </form>
                                    <form method="POST" class="d-inline me-1">
                                        <input type="hidden" name="jv_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action_type" value="reject">
                                        <button type="submit" class="btn btn-warning btn-sm px-3 btn-action">Reject</button>
                                    </form>
                                <?php endif; ?>

                                    <button type="button" class="btn btn-info btn-sm px-3 btn-action viewBtn" data-jv='<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>View</button>
                                    <form method="POST" class="d-inline deleteForm">
                                        <input type="hidden" name="jv_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action_type" value="delete">
                                        <button type="button" class="btn btn-danger btn-sm px-3 btn-action deleteBtn">Delete</button>
                                    </form>
                                <div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

            <?php include './include/footer.php'; ?>
      </div>

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="./assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="./assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="./assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="./assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="./assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="./assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="./assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="./assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Google Maps Plugin -->
    <script src="./assets/js/plugin/gmaps/gmaps.js"></script>

    <!-- Sweet Alert -->
    <script src="./assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="./assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="./assets/js/setting-demo2.js"></script>
    
    <!-- Sweet Alert -->
<script src="./assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 

<!-- JV Details Modal -->
<div class="modal fade" id="jvDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Joint Venture Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="jvDetailsContent">
          <!-- Details will load here dynamically -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--delete-->
    <script>
    document.querySelectorAll('.deleteBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('.deleteForm');

        Swal.fire({
            title: 'Are you sure?',
            text: "Once deleted, you won't be able to recover this record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Change button type dynamically and submit
                const hiddenSubmit = document.createElement('button');
                hiddenSubmit.type = 'submit';
                hiddenSubmit.style.display = 'none';
                form.appendChild(hiddenSubmit);
                hiddenSubmit.click();
            }
        });
    });
});

//view
document.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal(document.getElementById('jvDetailsModal'));
  const contentDiv = document.getElementById('jvDetailsContent');

  document.querySelectorAll('.viewBtn').forEach(btn => {
    btn.addEventListener('click', function() {
      const data = JSON.parse(this.getAttribute('data-jv'));
      
      let html = `
        <div class="table-responsive">
          <table class="table table-bordered">
            <tbody>
              <tr><th>ID</th><td>${data.id}</td></tr>
              <tr><th>Full Name</th><td>${data.full_name}</td></tr>
              <tr><th>Email</th><td>${data.email}</td></tr>
              <tr><th>Mobile</th><td>${data.mobile}</td></tr>
              <tr><th>Location</th><td>${data.location}</td></tr>
              <tr><th>Taluk</th><td>${data.taluk}</td></tr>
              <tr><th>District</th><td>${data.district}</td></tr>
              <tr><th>State</th><td>${data.state}</td></tr>
              <tr><th>Country</th><td>${data.country}</td></tr>
              <tr><th>Acre Extent</th><td>${data.acre_extent}</td></tr>
              <tr><th>Plot Extent</th><td>${data.plot_extent}</td></tr>
              <tr><th>Road Width</th><td>${data.road_width}</td></tr>
              <tr><th>Zone Classification</th><td>${data.zone_classification}</td></tr>
              <tr><th>Dry Land</th><td>${data.dry_land == 1 ? 'Yes' : 'No'}</td></tr>
              <tr><th>Wet Land</th><td>${data.wet_land == 1 ? 'Yes' : 'No'}</td></tr>
              <tr><th>Development Category</th><td>${data.development_category}</td></tr>
              <tr><th>Finance Zone</th><td>${data.finance_zone}</td></tr>
              <tr><th>Advance Amount</th><td>${data.advance_amount}</td></tr>
              <tr><th>Ratio</th><td>${data.ratio}</td></tr>
              <tr><th>Refundable</th><td>${data.refundable}</td></tr>
              <tr><th>Non Refundable</th><td>${data.non_refundable}</td></tr>
              <tr><th>Status</th><td>${data.user_status}</td></tr>
              <tr><th>Created At</th><td>${data.created_at}</td></tr>
            </tbody>
          </table>
        </div>
      `;

      contentDiv.innerHTML = html;
      modal.show();
    });
  });
});
</script>
  </body>
</html>
