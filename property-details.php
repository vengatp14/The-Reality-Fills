<?php
include './admin_TheProperty/include/db_connection.php';

$propertyId = (int)($_GET['id'] ?? 0);
if ($propertyId <= 0) die('Invalid property ID');

// Increment view count
$conn->query("UPDATE properties SET views = views + 1 WHERE id = $propertyId");

// Fetch property details
$result = $conn->query("SELECT * FROM properties WHERE id = $propertyId AND user_status='active'");
$property = $result->fetch_assoc();
if (!$property) die('Property not found');

// Handle images
$serverImageDir = $_SERVER['DOCUMENT_ROOT'] . '/TheProperty/admin_TheProperty/include/images/';
$browserImageDir = '/TheProperty/admin_TheProperty/include/images/';
$defaultImage = '/TheProperty/assets/img/default-property.png';

$imagesField = trim($property['property_images'] ?? '');
$images = [];

if (!empty($imagesField)) {
    $decoded = json_decode($imagesField, true);
    if (is_array($decoded)) $images = $decoded;
    else $images = array_map('trim', explode(',', $imagesField));
}

$firstImage = $defaultImage;
if (!empty($images) && file_exists($serverImageDir . basename($images[0]))) {
    $firstImage = $browserImageDir . basename($images[0]);
}

// Admin contact
$adminPhone = "+91 9543146766"; 

// Prepare map location
$mapAddress = urlencode($property['property_address']);
?>
<!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Property Details | The Realty Fills Real Estate Experts</title>
  <meta name="description" content="View detailed information about this property for sale or rental with The Realty Fills. Explore features, amenities, location, and contact our real estate team.">
  <meta name="keywords" content="property for sale, property rental, land for sale, buy property, real estate, residential property, commercial property, The Realty Fills">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
  .property-details-page .navbar {
    z-index: 2000 !important;
  }
  .property-details-page .contact-card.sticky-top {
    z-index: 500 !important;
  }
     h1 {
    font-size: 2rem; 
    font-weight: 600;
    margin-bottom: 0.5rem;
  }
    .main-property-img {
      max-width: 100%;
      height: 400px; 
      object-fit: cover; 
      border-radius: 8px;
    }

    .thumbnail-images img {
      width: 90px; 
      height: 60px; 
    }

    .property-gallery img { cursor: pointer; transition: 0.3s; }
    .property-gallery img:hover { transform: scale(1.05); }
    .main-property-img { max-width: 100%; height: auto; border-radius: 8px; }
    .arrow-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0,0,0,0.4);
        color: #fff;
        border: none;
        font-size: 1.5rem;
        padding: 0.2rem 0.5rem;
        cursor: pointer;
        border-radius: 50%;
        z-index: 10;
        transition: background-color 0.3s;
    }
    .arrow-btn.left { left: 5px; }
    .arrow-btn.right { right: 5px; }
    .arrow-btn:hover { background-color: rgba(0,0,0,0.7); }

    .thumbnail-images img {
        width: 100px; height: 70px; object-fit: cover; margin: 0.25rem; border-radius: 4px; cursor: pointer;
        transition: transform 0.2s;
    }
    .thumbnail-images img:hover { transform: scale(1.05); }
    .contact-card { background: #f8f9fa; padding: 1rem; border-radius: 8px; }
  </style>
</head>

<body class="property-details-page">

<?php include './assets/include/nav.php'; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Property Details</h1>
              <p class="mb-0">
                Discover this exceptional property for sale or rental with The Realty Fills. Explore premium features, modern amenities, and a prime location. Contact our real estate experts to buy property, arrange a viewing, or learn more about residential and commercial property opportunities.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Property Details</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Property Title & Info -->
  <div class="row mb-4">
    <div class="col-lg-8">
      <h1><?= htmlspecialchars($property['property_title']) ?></h1>
      <p class="text-muted property-address"><?= htmlspecialchars($property['property_address']) ?></p>
    </div>
  </div>

  <div class="row">
    <!-- Left Column: Gallery & Details -->
    <div class="col-lg-8">

      <!-- Gallery -->
      <div class="property-gallery position-relative text-center mb-4">
        <img id="main-image" src="<?= $firstImage ?>" class="main-property-img mb-3">
        <button class="arrow-btn left" id="prev-btn">&#10094;</button>
        <button class="arrow-btn right" id="next-btn">&#10095;</button>
      </div>

      <?php if(!empty($images)): ?>
      <div class="d-flex flex-wrap thumbnail-images mb-4">
        <?php foreach ($images as $img):
          $imgPath = file_exists($serverImageDir . basename($img)) ? $browserImageDir . basename($img) : $defaultImage;
        ?>
        <img src="<?= $imgPath ?>" onclick="document.getElementById('main-image').src='<?= $imgPath ?>'">
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Property Details -->
      <div class="property-info mb-4">
        <h3>Property Details</h3>
        <ul class="list-unstyled">
          <li><strong>Type:</strong> <?= htmlspecialchars($property['property_type']) ?></li>
          <li><strong>Listing:</strong> <?= htmlspecialchars($property['listing_type']) ?></li>
          <li><strong>Price:</strong> ₹<?= number_format($property['property_price'],2) ?></li>
          <li><strong>Size:</strong> <?= $property['property_size'] ?> <?= $property['property_unit'] ?></li>
          <li><strong>Created At:</strong> <?= $property['created_at'] ?></li>
        </ul>
      </div>

      <!-- Map -->
      <div class="property-map mb-4">
        <h3>Location</h3>
        <iframe
          src="https://www.google.com/maps?q=<?= $mapAddress ?>&output=embed"
          width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
      </div>

    </div>

    <!-- Right Column: Contact -->
    <div class="col-lg-4">
      <div class="contact-card sticky-top">
        <h4>Contact Agent</h4>
        <p><i class="bi bi-telephone-fill"></i> <?= $adminPhone ?></p>
        <hr>
        <h5>Schedule a Tour</h5>
        <form action="contact.php" method="post" class="php-email-form">
          <input type="text" name="name" class="form-control mb-2" placeholder="Your Name" required>
          <input type="email" name="email" class="form-control mb-2" placeholder="Your Email" required>
          <input type="tel" name="phone" class="form-control mb-2" placeholder="Your Phone">
          <input type="text" name="subject" class="form-control mb-2" placeholder="Subject">
          <textarea name="message" rows="3" class="form-control mb-2" placeholder="Message"></textarea>
          <button type="submit" class="btn btn-primary w-100">Send Request</button>
        </form>
      </div>
    </div>
  </div>
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
<!--Image Navigation-->
<script>
let images = <?php echo json_encode($images); ?>;
images = images.map(img => img ? '<?= $browserImageDir ?>' + img.split('/').pop() : '<?= $defaultImage ?>');
let currentIndex = 0;

document.getElementById('prev-btn').addEventListener('click', () => {
    if(images.length === 0) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById('main-image').src = images[currentIndex];
});

document.getElementById('next-btn').addEventListener('click', () => {
    if(images.length === 0) return;
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById('main-image').src = images[currentIndex];
});

//hidden address
document.addEventListener("DOMContentLoaded", function() {
  const addressElement = document.querySelector(".property-address"); 

  if (addressElement) {
    const fullAddress = addressElement.textContent.trim();

    // Regex
    const regex = /([A-Za-z\s]+,\s*[A-Za-z\s]+[-\s]*\d{6})$/;
    const match = fullAddress.match(regex);

    if (match) {
      addressElement.textContent = match[1].trim();
    } else {
      const words = fullAddress.split(/\s+/);
      addressElement.textContent = words.slice(-4).join(" ");
    }
  }
});
</script>
</body>
</html>