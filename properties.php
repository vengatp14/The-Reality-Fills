<?php
include './admin_TheProperty/include/db_connection.php';

session_start();

//admin number
$adminMobile = "+91 9543146766";

// Pagination setup
$limit = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

// Filters
$type = $_GET['property_type'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';
$loc = $_GET['property_address'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$listing_type = $_GET['listing_type'] ?? ''; 
$size = $_GET['property_size'] ?? '';
$unit = $_GET['property_unit'] ?? '';

// Base query
$query = "SELECT * FROM properties WHERE user_status='Active'";
if (!empty($type)) $query .= " AND property_type='$type'";
if (!empty($listing_type)) $query .= " AND listing_type='$listing_type'";
if (!empty($loc)) $query .= " AND property_address LIKE '%$loc%'";
if (!empty($min)) $query .= " AND property_price >= $min";
if (!empty($max)) $query .= " AND property_price <= $max";
if (!empty($size)) $query .= " AND property_size >= $size";
if (!empty($unit)) $query .= " AND property_unit='$unit'";


// Sorting
switch ($sort) {
  case 'low': $query .= " ORDER BY property_price ASC"; break;
  case 'high': $query .= " ORDER BY property_price DESC"; break;
  case 'views': $query .= " ORDER BY views DESC"; break;
  default: $query .= " ORDER BY id DESC"; break;
}

// Handle AJAX request for location suggestions
if (isset($_GET['ajax_location']) && !empty($_GET['term'])) {
    $term = $conn->real_escape_string($_GET['term']);

    $sql = "SELECT DISTINCT property_address 
            FROM properties 
            WHERE property_address LIKE '%$term%'
            LIMIT 10";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $used = [];
        while ($row = $result->fetch_assoc()) {
            $fullAddress = trim($row['property_address']);

            // Split by comma
            $parts = array_filter(array_map('trim', explode(',', $fullAddress)));

            // Take last 3 parts: area, city, ZIP
            if (count($parts) >= 3) {
                $shortAddress = implode(', ', array_slice($parts, -3));
            } else {
                $shortAddress = implode(', ', $parts); // fallback
            }

            // Avoid duplicates
            if (!in_array($shortAddress, $used)) {
                $used[] = $shortAddress;
                $shortAddressJS = htmlspecialchars($shortAddress, ENT_QUOTES);
                echo "<a href='#' class='list-group-item list-group-item-action' onclick='selectCity(\"$shortAddressJS\")'>$shortAddressJS</a>";
            }
        }
    } else {
        echo "<span class='list-group-item'>No results found</span>";
    }
    exit;
}

$query .= " LIMIT $start_from, $limit";
$result = $conn->query($query);

// Count total for pagination
$countQuery = "SELECT COUNT(*) AS total FROM properties WHERE user_status='Active'";
if (!empty($type)) $countQuery .= " AND property_type='$type'";
if (!empty($listing_type)) $countQuery .= " AND listing_type='$listing_type'";
if (!empty($loc)) $countQuery .= " AND property_address LIKE '%$loc%'";
if (!empty($min)) $countQuery .= " AND property_price >= $min";
if (!empty($max)) $countQuery .= " AND property_price <= $max";
if (!empty($size)) $countQuery .= " AND property_size >= $size";
if (!empty($unit)) $countQuery .= " AND property_unit='$unit'";


$countResult = $conn->query($countQuery);
$total = $countResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Properties for Sale & Rent | The Realty Fills Real Estate</title>
  <meta name="description" content="Browse property for sale, property rental, land for sale, and commercial or residential property listings with The Realty Fills. Find your ideal real estate today.">
  <meta name="keywords" content="property for sale, property rental, land for sale, buy property, real estate, residential property, commercial property, The Realty Fills">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/drift-zoom/drift-basic.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    #property-container.list-view .col-12.col-sm-6.col-md-4.col-lg-3 {
    flex: 1 1 100% !important; 
    max-width: 100% !important;
}

#property-container.list-view .card {
    flex-direction: row;
    align-items: center;
}

#property-container.list-view .card img {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 0.5rem 0 0 0.5rem;
}

#property-container.list-view .card-body {
    flex: 1;
    padding-left: 1rem;
}

#property-container .card {
  margin-bottom: 20px; 
}

#property-container .col-12.col-sm-6.col-md-4.col-lg-3 {
  padding-left: 8px;
  padding-right: 8px;
}

.scroll-dropdown {
  max-height: 150px;
  overflow-y: auto;   
}
    </style>
</head>

<body class="properties-page">

<?php include './assets/include/nav.php'; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Properties for Sale & Rent</h1>
              <p class="mb-0">
                Discover the best property for sale, property rental, land for sale, and commercial or residential property listings with The Realty Fills. Find your perfect home, investment, or business space with our expert real estate team.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Properties</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->
    
    <!-- Properties Section -->
    <section id="properties" class="properties section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">

          <div class="col-lg-8 col-12">

            <div class="properties-header mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                 <h4 class="fw-bold mb-0">Available Properties</h4>
                 <form method="get" class="d-flex align-items-center">
                  <label class="me-2">Sort:</label>
                  <select name="sort" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                    <option value="newest" <?=($sort=='newest'?'selected':'')?>>Newest</option>
                    <option value="low" <?=($sort=='low'?'selected':'')?>>Price: Low to High</option>
                    <option value="high" <?=($sort=='high'?'selected':'')?>>Price: High to Low</option>
                    <option value="views" <?=($sort=='views'?'selected':'')?>>Most Viewed</option>
                  </select>
                </form>
              </div>

            <!--toggle-->
            <div class="view-toggle d-flex gap-2 mb-3">
              <button class="btn btn-outline-secondary btn-sm view-btn active" data-view="grid">
                <i class="bi bi-grid"></i> Grid
              </button>
              <button class="btn btn-outline-secondary btn-sm view-btn" data-view="list">
                <i class="bi bi-list"></i> List
              </button>
            </div>
            <div id="property-container" class="row g-5">
                <?php
                $defaultImage = '/TheProperty/assets/img/default-property.png';
                $serverImageDir = $_SERVER['DOCUMENT_ROOT'] . '/TheProperty/admin_TheProperty/include/images/';
                $browserImageDir = '/TheProperty/admin_TheProperty/include/images/';

                if ($result->num_rows > 0):
                  while ($row = $result->fetch_assoc()):

                    //Image Handling
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
              <!--display grid-->
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                <div class="card shadow-sm border-0 rounded-4 flex-fill">
                  <img src="<?= htmlspecialchars($imagePath); ?>" class="card-img-top rounded-top-4 img-fluid" alt="Property Image" style="height:220px; object-fit:cover;">                     
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['property_title']); ?></h5>
                    <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['property_address']); ?></p>
                    <p class="text-muted mb-1"><i class="bi bi-house-door"></i> 
                    <?= htmlspecialchars($row['property_type']); ?> |
                    <?= htmlspecialchars($row['property_size'] . ' ' . $row['property_unit']); ?>
                  </p>
                  <p class="text-muted mb-1">
                    <i class="bi bi-tags"></i> <?= htmlspecialchars($row['listing_type']); ?>
                  </p>
                  <p class="text-muted mb-1"><i class="bi bi-person"></i> <?= htmlspecialchars($row['client_name']); ?></p>
                  <p class="text-muted mb-1"><i class="bi bi-telephone"></i> <?= $adminMobile ?></p>
                  <p class="fw-bold text-primary mb-2 mt-auto">₹<?= number_format($row['property_price']); ?></p>
                  <a href="property-details.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary w-100 mt-2 btn-sm text-truncate">View Details</a>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-center text-muted">No accepted properties found.</p>
        <?php endif; ?>
      </div>
                    <!--div class="property-content">
                      <div class="property-price">$875,000</div>
                      <h4 class="property-title">Modern Family Home with Garden</h4>
                      <p class="property-location"><i class="bi bi-geo-alt"></i> 2847 Oak Street, Beverly Hills, CA 90210</p>
                      <div class="property-features">
                        <span><i class="bi bi-house"></i> 4 Bed</span>
                        <span><i class="bi bi-water"></i> 3 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 2,400 sqft</span>
                      </div>
                      <div class="property-agent">
                        <img src="assets/img/real-estate/agent-1.webp" alt="Agent" class="agent-avatar">
                        <div class="agent-info">
                          <strong>Sarah Johnson</strong>
                          <div class="agent-contact">
                            <small><i class="bi bi-telephone"></i> +1 (555) 123-4567</small>
                          </div>
                        </div>
                      </div>
                      <a href="#" class="btn btn-primary w-100">View Details</a>
                    </div>
                  </div>
                </div--><!-- End Property Item -->

                <!--div class="col-lg-6 col-md-6" data-tag="newest">
                  <div class="property-card">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-exterior-3.webp" alt="Downtown Luxury Condo" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge new">New</span>
                        <span class="badge for-sale">For Sale</span>
                      </div>
                      <div class="property-overlay">
                        <button class="favorite-btn"><i class="bi bi-heart"></i></button>
                        <button class="gallery-btn" data-count="8"><i class="bi bi-images"></i></button>
                      </div>
                    </div>
                    <div class="property-content">
                      <div class="property-price">$1,250,000</div>
                      <h4 class="property-title">Downtown Luxury Condominium</h4>
                      <p class="property-location"><i class="bi bi-geo-alt"></i> 1542 Main Avenue, Manhattan, NY 10001</p>
                      <div class="property-features">
                        <span><i class="bi bi-house"></i> 2 Bed</span>
                        <span><i class="bi bi-water"></i> 2 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 1,800 sqft</span>
                      </div>
                      <div class="property-agent">
                        <img src="assets/img/real-estate/agent-3.webp" alt="Agent" class="agent-avatar">
                        <div class="agent-info">
                          <strong>Michael Chen</strong>
                          <div class="agent-contact">
                            <small><i class="bi bi-telephone"></i> +1 (555) 234-5678</small>
                          </div>
                        </div>
                      </div>
                      <a href="#" class="btn btn-primary w-100">View Details</a>
                    </div>
                  </div>
                </div--><!-- End Property Item -->

                <!--div class="col-lg-6 col-md-6" data-tag="views">
                  <div class="property-card">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-interior-4.webp" alt="Suburban Villa" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge for-rent">For Rent</span>
                      </div>
                      <div class="property-overlay">
                        <button class="favorite-btn"><i class="bi bi-heart"></i></button>
                        <button class="gallery-btn" data-count="15"><i class="bi bi-images"></i></button>
                      </div>
                    </div>
                    <div class="property-content">
                      <div class="property-price">$4,500<span>/month</span></div>
                      <h4 class="property-title">Spacious Suburban Villa</h4>
                      <p class="property-location"><i class="bi bi-geo-alt"></i> 789 Pine Ridge Drive, Austin, TX 73301</p>
                      <div class="property-features">
                        <span><i class="bi bi-house"></i> 5 Bed</span>
                        <span><i class="bi bi-water"></i> 4 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 3,200 sqft</span>
                      </div>
                      <div class="property-agent">
                        <img src="assets/img/real-estate/agent-5.webp" alt="Agent" class="agent-avatar">
                        <div class="agent-info">
                          <strong>Emma Rodriguez</strong>
                          <div class="agent-contact">
                            <small><i class="bi bi-telephone"></i> +1 (555) 345-6789</small>
                          </div>
                        </div>
                      </div>
                      <a href="#" class="btn btn-primary w-100">View Details</a>
                    </div>
                  </div>
                </div--><!-- End Property Item -->

                <!--div class="col-lg-6 col-md-6">
                  <div class="property-card">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-exterior-6.webp" alt="Waterfront Townhouse" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge open-house">Open House</span>
                        <span class="badge for-sale">For Sale</span>
                      </div>
                      <div class="property-overlay">
                        <button class="favorite-btn"><i class="bi bi-heart"></i></button>
                        <button class="gallery-btn" data-count="20"><i class="bi bi-images"></i></button>
                      </div>
                    </div>
                    <div class="property-content">
                      <div class="property-price">$695,000</div>
                      <h4 class="property-title">Waterfront Townhouse with Dock</h4>
                      <p class="property-location"><i class="bi bi-geo-alt"></i> 456 Harbor View Lane, Miami, FL 33101</p>
                      <div class="property-features">
                        <span><i class="bi bi-house"></i> 3 Bed</span>
                        <span><i class="bi bi-water"></i> 2 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 2,100 sqft</span>
                      </div>
                      <div class="property-agent">
                        <img src="assets/img/real-estate/agent-7.webp" alt="Agent" class="agent-avatar">
                        <div class="agent-info">
                          <strong>David Williams</strong>
                          <div class="agent-contact">
                            <small><i class="bi bi-telephone"></i> +1 (555) 456-7890</small>
                          </div>
                        </div>
                      </div>
                      <a href="#" class="btn btn-primary w-100">View Details</a>
                    </div>
                  </div>
                </div--><!-- End Property Item -->

              <!--/div>
            </div>

            <div class="properties-list view-list" data-aos="fade-up" data-aos-delay="200">

              <div class="property-list-item">
                <div class="row align-items-center">
                  <div class="col-lg-4">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-exterior-1.webp" alt="Modern Family Home" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge featured">Featured</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="property-content">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h4 class="property-title mb-1">Modern Family Home with Garden</h4>
                          <p class="property-location mb-2"><i class="bi bi-geo-alt"></i> 2847 Oak Street, Beverly Hills, CA 90210</p>
                        </div>
                        <div class="property-price">$875,000</div>
                      </div>
                      <div class="property-features mb-3">
                        <span><i class="bi bi-house"></i> 4 Bed</span>
                        <span><i class="bi bi-water"></i> 3 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 2,400 sqft</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="property-agent">
                          <img src="assets/img/real-estate/agent-1.webp" alt="Agent" class="agent-avatar">
                          <span>Sarah Johnson</span>
                        </div>
                        <div class="property-actions">
                          <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-heart"></i></button>
                          <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div--><!-- End Property List Item -->

              <!--div class="property-list-item">
                <div class="row align-items-center">
                  <div class="col-lg-4">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-exterior-3.webp" alt="Downtown Luxury Condo" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge new">New</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="property-content">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h4 class="property-title mb-1">Downtown Luxury Condominium</h4>
                          <p class="property-location mb-2"><i class="bi bi-geo-alt"></i> 1542 Main Avenue, Manhattan, NY 10001</p>
                        </div>
                        <div class="property-price">$1,250,000</div>
                      </div>
                      <div class="property-features mb-3">
                        <span><i class="bi bi-house"></i> 2 Bed</span>
                        <span><i class="bi bi-water"></i> 2 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 1,800 sqft</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="property-agent">
                          <img src="assets/img/real-estate/agent-3.webp" alt="Agent" class="agent-avatar">
                          <span>Michael Chen</span>
                        </div>
                        <div class="property-actions">
                          <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-heart"></i></button>
                          <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div--><!-- End Property List Item -->

              <!--div class="property-list-item">
                <div class="row align-items-center">
                  <div class="col-lg-4">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-interior-4.webp" alt="Suburban Villa" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge for-rent">For Rent</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="property-content">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h4 class="property-title mb-1">Spacious Suburban Villa</h4>
                          <p class="property-location mb-2"><i class="bi bi-geo-alt"></i> 789 Pine Ridge Drive, Austin, TX 73301</p>
                        </div>
                        <div class="property-price">$4,500<span>/month</span></div>
                      </div>
                      <div class="property-features mb-3">
                        <span><i class="bi bi-house"></i> 5 Bed</span>
                        <span><i class="bi bi-water"></i> 4 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 3,200 sqft</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="property-agent">
                          <img src="assets/img/real-estate/agent-5.webp" alt="Agent" class="agent-avatar">
                          <span>Emma Rodriguez</span>
                        </div>
                        <div class="property-actions">
                          <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-heart"></i></button>
                          <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div--><!-- End Property List Item -->

              <!--div class="property-list-item">
                <div class="row align-items-center">
                  <div class="col-lg-4">
                    <div class="property-image">
                      <img src="assets/img/real-estate/property-exterior-6.webp" alt="Waterfront Townhouse" class="img-fluid">
                      <div class="property-badges">
                        <span class="badge open-house">Open House</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="property-content">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h4 class="property-title mb-1">Waterfront Townhouse with Dock</h4>
                          <p class="property-location mb-2"><i class="bi bi-geo-alt"></i> 456 Harbor View Lane, Miami, FL 33101</p>
                        </div>
                        <div class="property-price">$695,000</div>
                      </div>
                      <div class="property-features mb-3">
                        <span><i class="bi bi-house"></i> 3 Bed</span>
                        <span><i class="bi bi-water"></i> 2 Bath</span>
                        <span><i class="bi bi-arrows-angle-expand"></i> 2,100 sqft</span>
                      </div>
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="property-agent">
                          <img src="assets/img/real-estate/agent-7.webp" alt="Agent" class="agent-avatar">
                          <span>David Williams</span>
                        </div>
                        <div class="property-actions">
                          <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-heart"></i></button>
                          <a href="#" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div--><!-- End Property List Item -->

            <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item <?=($page<=1?'disabled':'')?>"><a class="page-link" href="?page=<?=$page-1?>">Previous</a></li>
            <?php for ($i=1;$i<=$totalPages;$i++): ?>
              <li class="page-item <?=($i==$page?'active':'')?>"><a class="page-link" href="?page=<?=$i?>"><?=$i?></a></li>
            <?php endfor; ?>
            <li class="page-item <?=($page>=$totalPages?'disabled':'')?>"><a class="page-link" href="?page=<?=$page+1?>">Next</a></li>
          </ul>
        </nav>
      </div>

      <!--Sidebar -->
      <div class="col-lg-4 col-12">
        <div class="properties-sidebar">

              <div class="filter-widget mb-4">
                <h5 class="filter-title">Filter Properties</h5>
                <form method="get" action="">

                 <!-- Property Type -->
                <div class="filter-section mb-3">
                  <label class="form-label">Property Type</label>
                  <select class="form-select scroll-dropdown" name="property_type">
                    <option value="">All Types</option>
                    <option value="House" <?=(($_GET['property_type'] ?? '')=='House'?'selected':'')?>>House</option>
                    <option value="Villa" <?=(($_GET['property_type'] ?? '')=='Apartment'?'selected':'')?>>Villa</option>
                    <option value="Bungalow" <?=(($_GET['property_type'] ?? '')=='Apartment'?'selected':'')?>>Bungalow</option>
                    <option value="Apartment" <?=(($_GET['property_type'] ?? '')=='Apartment'?'selected':'')?>>Apartment</option>
                    <option value="Flat" <?=(($_GET['property_type'] ?? '')=='Flat'?'selected':'')?>>Flat</option>
                    <option value="Condo" <?=(($_GET['property_type'] ?? '')=='Condo'?'selected':'')?>>Condo</option>
                    <option value="Townhouse" <?=(($_GET['property_type'] ?? '')=='Townhouse'?'selected':'')?>>Townhouse</option>
                    <option value="Commercial" <?=(($_GET['property_type'] ?? '')=='Commercial'?'selected':'')?>>Commercial</option>
                    <option value="Commercial Plot" <?=(($_GET['property_type'] ?? '')=='Commercial Plot'?'selected':'')?>>Commercial Plot</option>
                    <option value="Residential Plot" <?=(($_GET['property_type'] ?? '')=='Residential Plot'?'selected':'')?>>Residential Plot</option>
                    <option value="Rental Returns Property" <?=(($_GET['property_type'] ?? '')=='Rental Returns Property'?'selected':'')?>>Rental Returns Property</option>
                    <option value="Industrial Land" <?=(($_GET['property_type'] ?? '')=='Industrial Land'?'selected':'')?>>Industrial Land</option>
                    <option value="Empty Land" <?=(($_GET['property_type'] ?? '')=='Empty Land'?'selected':'')?>>Empty Land</option>
                    <option value="Farm Land" <?=(($_GET['property_type'] ?? '')=='Farm Land'?'selected':'')?>>Farm Land</option>
                    <option value="Agricultural Land" <?=(($_GET['property_type'] ?? '')=='Agricultural Land'?'selected':'')?>>Agricultural Land</option>
                  </select>
                </div>

                <!-- Listing Type -->
                 <div class="filter-section mb-3">
                  <label class="form-label">Listing Type</label>
                  <select class="form-select" name="listing_type">
                    <option value="">All</option>
                    <option value="Buy" <?= ($listing_type == 'Buy' ? 'selected' : '') ?>>Buy</option>
                    <option value="Sale" <?= ($listing_type == 'Sale' ? 'selected' : '') ?>>Sell</option>
                    <option value="Rent" <?= ($listing_type == 'Rent' ? 'selected' : '') ?>>Rent</option>
                    <option value="Lease" <?= ($listing_type == 'Lease' ? 'selected' : '') ?>>Lease</option>
                  </select>
                </div>

                <!--Price Range-->
                <div class="filter-section mb-3">
                  <label class="form-label">Price Range</label>
                  <div class="row g-2">
                    <div class="col-6">
                      <input type="number" class="form-control" placeholder="Min Price" name="min" value="<?=htmlspecialchars($_GET['min'] ?? '')?>">
                    </div>
                    <div class="col-6">
                      <input type="number" class="form-control" placeholder="Max Price" name="max" value="<?=htmlspecialchars($_GET['max'] ?? '')?>">
                    </div>
                  </div>
                </div>

                <!-- Property Size -->
                 <div class="filter-section mb-3">
                  <label class="form-label">Property Size</label>
                  <div class="row g-2">
                    <div class="col-6">
                  <input type="number" name="property_size" class="form-control" placeholder="Enter property size" value="<?= isset($_GET['property_size']) ? htmlspecialchars($_GET['property_size']) : ''; ?>">
                </div>
                <div class="col-6">
                  <select name="property_unit" class="form-select">
                    <option value="">Select Unit</option>
                    <option value="sqft" <?= (isset($_GET['property_unit']) && $_GET['property_unit'] == 'sqft') ? 'selected' : ''; ?>>Sqft</option>
                    <option value="sq.m" <?= (isset($_GET['property_unit']) && $_GET['property_unit'] == 'sq.m') ? 'selected' : ''; ?>>Sq. Meter</option>
                    <option value="cent" <?= (isset($_GET['property_unit']) && $_GET['property_unit'] == 'cent') ? 'selected' : ''; ?>>Cent</option>
                    <option value="acre" <?= (isset($_GET['property_unit']) && $_GET['property_unit'] == 'acre') ? 'selected' : ''; ?>>Acre</option>
                  </select>
                </div>
            </div>
          </div>
          
                <!-- Location -->
                 <div class="filter-section mb-3">
                  <label class="form-label">Location</label>
                  <input type="text" id="locationInput" name="property_address" class="form-control" 
                    placeholder="Enter city or neighborhood" 
                    value="<?=htmlspecialchars($_GET['property_address'] ?? '')?>" onkeyup="showSuggestions()">
                  <div id="suggestions" class="list-group"></div>
                </div>

                <button class="btn btn-primary w-100">Apply Filters</button>
              </form>
          </div>
              
              <!--div class="featured-properties mt-4">
                <h5>Featured Properties</h5>

                <div class="featured-item">
                  <div class="row g-3 align-items-center">
                    <div class="col-5">
                      <img src="assets/img/real-estate/property-exterior-8.webp" alt="Property" class="img-fluid rounded">
                    </div>
                    <div class="col-7">
                      <h6 class="mb-1">Luxury Penthouse</h6>
                      <p class="text-muted small mb-1">Manhattan, NY</p>
                      <strong class="text-primary">$2,850,000</strong>
                    </div>
                  </div>
                </div>

                <div class="featured-item">
                  <div class="row g-3 align-items-center">
                    <div class="col-5">
                      <img src="assets/img/real-estate/property-interior-7.webp" alt="Property" class="img-fluid rounded">
                    </div>
                    <div class="col-7">
                      <h6 class="mb-1">Modern Studio</h6>
                      <p class="text-muted small mb-1">Brooklyn, NY</p>
                      <strong class="text-primary">$3,200/mo</strong>
                    </div>
                  </div>
                </div>

                <div class="featured-item">
                  <div class="row g-3 align-items-center">
                    <div class="col-5">
                      <img src="assets/img/real-estate/property-exterior-9.webp" alt="Property" class="img-fluid rounded">
                    </div>
                    <div class="col-7">
                      <h6 class="mb-1">Family Home</h6>
                      <p class="text-muted small mb-1">Queens, NY</p>
                      <strong class="text-primary">$895,000</strong>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
</section--><!-- /Properties Section -->

  </main>

  <?php include './assets/include/footer.php'; ?>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/drift-zoom/Drift.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
<style>
/* Keep both sections aligned properly */
.property-section .row {
  align-items: flex-start !important;
}

/* Adjust sidebar height alignment */
.property-section .col-lg-4 {
  margin-top: -50px !important;  /* less negative so title shows fully */
}

/* Fix for smaller screens */
@media (max-width: 991px) {
  .property-section .col-lg-4 {
    margin-top: 20px !important;
  }
}
</style>
<!--Location Search-->
<script>
  function showSuggestions() {
    let input = document.getElementById('locationInput').value;
    let suggestions = document.getElementById('suggestions');

    if (input.length < 1) {
        suggestions.innerHTML = '';
        return;
    }

    fetch('properties.php?ajax_location=1&term=' + encodeURIComponent(input))
        .then(response => response.text())
        .then(data => suggestions.innerHTML = data);
}

function selectCity(city) {
    document.getElementById('locationInput').value = city;
    document.getElementById('suggestions').innerHTML = '';
}

//toggle
const viewButtons = document.querySelectorAll('.view-btn');
const propertyContainer = document.getElementById('property-container');

viewButtons.forEach(btn => {
  btn.addEventListener('click', () => {
    const view = btn.getAttribute('data-view');

    // Toggle active class
    viewButtons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Toggle grid/list classes
    if(view === 'list'){
      propertyContainer.classList.add('list-view');
    } else {
      propertyContainer.classList.remove('list-view');
    }
  });
});

//hidden address
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.card .text-muted.mb-1 i.bi-geo-alt').forEach(icon => {
    const p = icon.parentElement;
    const fullAddress = p.textContent.trim();

    //regex
    const regex = /([A-Z][a-zA-Z\s]+,\s*[A-Za-z\s]+[-\s]*\d{6})$/;
    const match = fullAddress.match(regex);

    if (match) {
      p.innerHTML = '<i class="bi bi-geo-alt"></i> ' + match[1].trim();
    } else {
      const words = fullAddress.split(/\s+/);
      const shortAddress = words.slice(-4).join(' ');
      p.innerHTML = '<i class="bi bi-geo-alt"></i> ' + shortAddress;
    }
  });
});

function selectCity(city) {
    document.getElementById('locationInput').value = city;
    document.getElementById('suggestions').innerHTML = '';
}

document.getElementById('locationInput').addEventListener('input', function() {
    const input = this.value.trim();
    const suggestions = document.getElementById('suggestions');

    if (input.length < 1) {
        suggestions.innerHTML = '';
        return;
    }

    fetch('properties.php?ajax_location=1&term=' + encodeURIComponent(input))
        .then(res => res.text())
        .then(data => suggestions.innerHTML = data)
        .catch(err => console.error(err));
});


  </script>
</body>
</html>