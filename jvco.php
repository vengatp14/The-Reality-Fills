<?php
include './admin_TheProperty/include/db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "<h2>Form submitted successfully!</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="en">

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
              <h1 class="heading-title">JVCo Details</h1>
              <p class="mb-0">
                Our Joint Venture Company collaborates with leading real estate developers to create premium residential and commercial projects. Together, we combine expertise, innovation, and trust to deliver exceptional property solutions.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">JVCo</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

    <!-- Joint Venture Proposal Form -->
<section id="joint-venture" class="joint-venture section py-5">
  <div class="container" data-aos="fade-up">
    <div class="row">
      <div class="col-lg-12">
        <h3 class="mb-3">Joint Venture Proposal</h3>
        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Your proposal was submitted successfully!</div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="alert alert-danger">Something went wrong. Please try again.</div>
                <?php endif; ?>
                <form action="./admin_TheProperty/jvco_admin.php" method="POST" id="jointVentureForm">

        <!--Individual Identification-->
          <h5 class="mt-4 mb-3">Individual Identification</h5>
          <div class="mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input id="fullName" name="fullName" type="text" class="form-control" placeholder="Enter full name" required>
            <div class="invalid-feedback">Please enter your full name.</div>
        </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" placeholder="Enter email address" required
            value="<?php echo htmlspecialchars($_SESSION['user'] ?? ''); ?>">
            <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile</label>
            <input id="mobile" name="mobile" type="tel" pattern="[0-9]{10}" class="form-control" placeholder="Enter 10-digit mobile number" required>
            <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
        </div>
          
          <!-- Location Details -->
          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input id="location" name="location" type="text" class="form-control" placeholder="Enter location" required>
            <div class="invalid-feedback">Please enter location.</div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="taluk" class="form-label">Taluk</label>
              <input id="taluk" name="taluk" type="text" class="form-control" placeholder="Enter taluk" required>
              <div class="invalid-feedback">Please enter taluk.</div>
            </div>
            <div class="col-md-6">
              <label for="district" class="form-label">District</label>
              <input id="district" name="district" type="text" class="form-control" placeholder="Enter district" required>
              <div class="invalid-feedback">Please enter district.</div>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="state" class="form-label">State</label>
              <input id="state" name="state" type="text" class="form-control" placeholder="Enter state" required>
              <div class="invalid-feedback">Please enter state.</div>
            </div>
            <div class="col-md-6">
              <label for="country" class="form-label">Country</label>
              <input id="country" name="country" type="text" class="form-control" placeholder="Enter country" required>
              <div class="invalid-feedback">Please enter country.</div>
            </div>
          </div>

          <!-- Land Proposal Details -->
          <h5 class="mt-4 mb-3">Land Proposal Details</h5>
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <label for="acreExtent" class="form-label">Acre Extent</label>
              <input id="acreExtent" name="acreExtent" type="text" class="form-control" placeholder="Enter acre extent" required>
              <div class="invalid-feedback">Please enter acre extent.</div>
            </div>
            <div class="col-md-4">
              <label for="plotExtent" class="form-label">Plot Extent</label>
              <input id="plotExtent" name="plotExtent" type="text" class="form-control" placeholder="Enter plot extent" required>
              <div class="invalid-feedback">Please enter plot extent.</div>
            </div>
            <div class="col-md-4">
              <label for="roadWidth" class="form-label">Road Width</label>
              <input id="roadWidth" name="roadWidth" type="text" class="form-control" placeholder="Enter road width" required>
              <div class="invalid-feedback">Please enter road width.</div>
            </div>
          </div>

          <div class="mb-3">
            <label for="zoneClassification" class="form-label">Zone Classification</label>
            <input id="zoneClassification" name="zoneClassification" type="text" class="form-control" placeholder="Enter zone classification" required>
            <div class="invalid-feedback">Please enter zone classification.</div>
          </div>

          <div class="mb-3">
            <label class="form-label d-block">Land Type</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="dryLand" name="dryLand">
              <label class="form-check-label" for="dryLand">Dry Land</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="wetLand" name="wetLand">
              <label class="form-check-label" for="wetLand">Wet Land</label>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label d-block">Development Category</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="farmLand" name="developmentCategory[]" value="Farm Land">
              <label class="form-check-label" for="farmLand">Farm Land</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="resVillas" name="developmentCategory[]" value="Residential Villas">
              <label class="form-check-label" for="resVillas">Residential Villas</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="apartments" name="developmentCategory[]" value="Apartments">
              <label class="form-check-label" for="apartments">Apartments</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="resPlotting" name="developmentCategory[]" value="Residential Plotting">
              <label class="form-check-label" for="resPlotting">Residential Plotting</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="comPlotting" name="developmentCategory[]" value="Commercial Plotting">
              <label class="form-check-label" for="comPlotting">Commercial Plotting</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="indPlotting" name="developmentCategory[]" value="Industrial Plotting">
              <label class="form-check-label" for="indPlotting">Industrial Plotting</label>
            </div>
          </div>

          <!-- Financial Details -->
          <h5 class="mt-4 mb-3">Financial Details</h5>
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="financeZone" class="form-label">Zone Classification</label>
              <input id="financeZone" name="financeZone" type="text" class="form-control" placeholder="Enter zone classification" required>
              <div class="invalid-feedback">Please enter zone classification.</div>
            </div>
            <div class="col-md-6">
              <label for="advanceAmount" class="form-label">Advance (₹)</label>
              <input id="advanceAmount" name="advanceAmount" type="number" class="form-control" placeholder="Enter advance amount" required>
              <div class="invalid-feedback">Please enter advance amount.</div>
            </div>
          </div>

          <div class="mb-3">
            <label for="ratio" class="form-label">Ratio</label>
            <input id="ratio" name="ratio" type="text" class="form-control" placeholder="Enter ratio" required>
            <div class="invalid-feedback">Please enter ratio.</div>
          </div>

          <div class="mb-3">
            <label class="form-label d-block">Refundable</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="refundableYes" name="refundable" value="Yes">
              <label class="form-check-label" for="refundableYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="refundableNo" name="refundable" value="No">
              <label class="form-check-label" for="refundableNo">No</label>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label d-block">Non-Refundable</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="nonRefundableYes" name="nonRefundable" value="Yes">
              <label class="form-check-label" for="nonRefundableYes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="nonRefundableNo" name="nonRefundable" value="No">
              <label class="form-check-label" for="nonRefundableNo">No</label>
            </div>
          </div>

          <div class="d-grid">
            <button id="submitJVProposal" type="submit" class="btn btn-primary">Submit Joint Venture Proposal</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
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

  <!--submit alert-->
  <script>
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("status") === "success") {
      alert("Your Joint Venture proposal has been submitted successfully!");
      window.history.replaceState({}, document.title, "jvco.php");
  } else if (urlParams.get("status") === "error") {
      alert("There was an error submitting your proposal. Please try again.");
      window.history.replaceState({}, document.title, "jvco.php");
  }
</script>
  </body>

</html>