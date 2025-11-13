<?php
include 'include/db_connection.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>property details - Kaiadmin Bootstrap 5 Admin Dashboard</title>
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
       <?php include './include/side_nav.php'; ?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <?php include './include/nav.php'; ?>

        <div class="container">
          <div class="page-inner">
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
              <div class="alert alert-success text-center">Property status updated successfully!</div>
              <?php endif; ?>
            <div class="page-header mb-0">
              <h3 class="fw-bold mb-3">property details</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="#">
                    <i class="icon-home"></i>
                  </a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Maps</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">property details</a>
                </li>
              </ul>
            </div>
            <div class="page-category">Help users find your property Details.</div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  </div>
                  <div class="card-body">
                   <!-- Property Card Section -->
  <!--section class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
          <img src="https://thumbs.dreamstime.com/b/modern-house-interior-exterior-design-46517595.jpg" 
               class="card-img-top rounded-top-4" alt="Luxury 3BHK Apartment">
          
          <div class="card-body">
            <h5 class="card-title fw-bold text-primary mb-2">Luxury 3BHK Apartment</h5>
            <p class="text-muted mb-1">
              Apartment • 1800 sqft • 
              <span class="fw-semibold text-success">Open</span>
            </p>

            <div class="mt-3 small">
              <p><strong>Owner:</strong> John Doe</p>
              <p><strong>Phone:</strong> 9876543210</p>
              <p><strong>Address:</strong> 123 Main Street, Mumbai</p>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button class="btn btn-success d-flex align-items-center gap-2 px-4">
                <i class="bi bi-check-circle-fill"></i> Accept
              </button>
              <button class="btn btn-outline-danger d-flex align-items-center gap-2 px-4">
                <i class="bi bi-x-circle-fill"></i> Reject
              </button>
            </div>
          </div>
        </div>
      </div>

       <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
          <img src="https://thumbs.dreamstime.com/b/modern-house-interior-exterior-design-46517595.jpg" 
               class="card-img-top rounded-top-4" alt="Luxury 3BHK Apartment">
          
          <div class="card-body">
            <h5 class="card-title fw-bold text-primary mb-2">Luxury 3BHK Apartment</h5>
            <p class="text-muted mb-1">
              Apartment • 1800 sqft • 
              <span class="fw-semibold text-success">Open</span>
            </p>

            <div class="mt-3 small">
              <p><strong>Owner:</strong> John Doe</p>
              <p><strong>Phone:</strong> 9876543210</p>
              <p><strong>Address:</strong> 123 Main Street, Mumbai</p>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <button class="btn btn-success d-flex align-items-center gap-2 px-4">
                <i class="bi bi-check-circle-fill"></i> Accept
              </button>
              <button class="btn btn-outline-danger d-flex align-items-center gap-2 px-4">
                <i class="bi bi-x-circle-fill"></i> Reject
              </button>
            </div>
          </div>
        </div>
      </div>

       <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
          <img src="https://thumbs.dreamstime.com/b/modern-house-interior-exterior-design-46517595.jpg" 
               class="card-img-top rounded-top-4" alt="Luxury 3BHK Apartment">
          
          <div class="card-body">
            <h5 class="card-title fw-bold text-primary mb-2">Luxury 3BHK Apartment</h5>
            <p class="text-muted mb-1">
              Apartment • 1800 sqft • 
              <span class="fw-semibold text-success">Open</span>
            </p>

            <div class="mt-3 small">
              <p><strong>Owner:</strong> John Doe</p>
              <p><strong>Phone:</strong> 9876543210</p>
              <p><strong>Address:</strong> 123 Main Street, Mumbai</p>
            </div-->
            
            <?php
            $limit = 3;
            // Current page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;
            $start = ($page - 1) * $limit;
            
            // Count total properties
            $countQuery = "SELECT COUNT(*) AS total FROM properties WHERE status = 'pending'";
            $countResult = $conn->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $total = $countRow['total'];
            $totalPages = ceil($total / $limit);

            // Fetch properties 
            $sql = "SELECT * FROM properties WHERE status = 'pending' LIMIT $start, $limit";
            $result = $conn->query($sql);
            ?>
            <div class="container py-4">
              <div class="row g-4 justify-content-center">
              <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                // Default image
                $imagePath = '/TheProperty/assets/img/default-property.png';

                //Images storage
                $serverImageDir = $_SERVER['DOCUMENT_ROOT'] . '/TheProperty/admin_TheProperty/include/images/';

                // Get DB field
                $imagesField = $row['property_images'] ?? '';

                // Decode JSON
                $images = json_decode($imagesField, true);
                if (!is_array($images) || empty($imagesField)) {
                  $images = [];
                }

                // Find first valid image
                $firstImage = '';
                foreach ($images as $img) {
                  if (!empty($img)) {
                    $firstImage = trim(basename($img));
                    break;
                  }
                }
    
                // Check existance
                if (!empty($firstImage)) {
                  $serverFile = $_SERVER['DOCUMENT_ROOT'] . '/TheProperty/admin_TheProperty/include/images/' . $firstImage;
                  if (file_exists($serverFile)) {
                    //Browser path
                    $imagePath = '/TheProperty/admin_TheProperty/include/images/' . $firstImage;
                  } else {
                    echo "<!-- File not found: $serverFile -->";
                  }
                } else {
                  echo "<!-- No valid image in property_images field -->";
                }
            ?>
            
            <!-- Property Card -->
             <div class="col-md-6 col-lg-4 d-flex">
              <div class="card shadow-lg border-0 rounded-4 flex-fill" style="min-height: 100%;">
                <img src="<?php echo $imagePath; ?>" 
                    class="card-img-top rounded-top-4" 
                    alt="Property Image" 
                    style="height: 220px; object-fit: cover;">

              <div class="card-body">
                <h5 class="card-title fw-bold text-primary mb-2">
                  <?php echo htmlspecialchars($row['property_title']); ?>
                </h5>
                <?php 
                $statusClass = ($row['status'] == 'accepted') ? 'text-success' : (($row['status'] == 'rejected') ? 'text-danger' : 'text-warning'); 
                ?>
                <p class="text-muted mb-1">
                  <span class="fw-bold text-primary"><?php echo htmlspecialchars($row['listing_type']); ?></span> •
                  <?php echo htmlspecialchars($row['property_type']); ?> • 
                  <?php echo htmlspecialchars($row['property_size'] . ' ' . $row['property_unit']); ?> •
                  <span class="<?php echo $statusClass; ?> text-success fw-semibold"><?php echo htmlspecialchars($row['status']); ?></span>
                </p>

                <div class="mt-3 small">
                  <p><strong>Owner:</strong> <?php echo htmlspecialchars($row['client_name']); ?></p>
                  <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['client_mobile']); ?></p>
                  <p><strong>Address:</strong> <?php echo htmlspecialchars($row['property_address']); ?></p>
                </div>
                              
                <!--Accept-->
                <div style="display: flex; align-items: center; margin-top: 20px;">
                    <form action="update_status.php" method="POST" style="margin: 0;">
                      <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                      <input type="hidden" name="status" value="accepted">
                      <button type="submit" class="btn btn-success px-4">Accept</button>
                </form>
              <!--Reject-->
                <form action="update_status.php" method="POST" style="margin-left: 100px; margin: 0;">
                  <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                  <input type="hidden" name="status" value="rejected">
                  <button type="submit" class="btn btn-outline-danger px-4" style="margin-left: 100px;">Reject</button>               
                </form>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">No properties found.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Pagination Navigation -->
<nav aria-label="Page navigation">
  <ul class="pagination justify-content-center mt-4">
    <!-- Previous Button -->
    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
    </li>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next Button -->
    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
      <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
    </li>
  </ul>
</nav>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php include './include/footer.php'; ?>
        </div>
      </div>

      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="./assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="./assets/js/core/popper.min.js"></script>
    <script src="./assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="./assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="./assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="./assets/js/setting-demo2.js"></script>
  </body>
</html>
