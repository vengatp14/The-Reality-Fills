<?php
include '../admin_TheProperty/include/db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $clientName = htmlspecialchars($_POST['clientName'] ?? '');
    $clientMobile = $_POST['clientMobile'] ?? '';
    $client_email = $_POST['clientEmail'] ?? '';
    $property_price = floatval($_POST['propertyPrice'] ?? 0);
    $propertyTitle = $_POST['propertyTitle'] ?? '';
    $listingType = $_POST['listingType'] ?? '';
    $propertyType = $_POST['propertyType'] ?? '';
    $propertySize = floatval($_POST['propertySize'] ?? 0);
    $propertyUnit = $_POST['propertyUnit'] ?? '';
    $propertyFulladd = $_POST['propertyFulladd'] ?? '';
    $propertyAddress = htmlspecialchars($_POST['propertyAddress'] ?? '');

    // Handle image upload
    $imageNames = [];

    if (!empty($_FILES['propertyImage']['name'][0])) {
    $targetDir =  __DIR__ . "/include/images/";

    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

    foreach ($_FILES['propertyImage']['name'] as $key => $name) {
      if (count($imageNames) >= 5) break;
        if (!empty($name)) {
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            //Validate file type
            if (!in_array($ext, $allowedTypes)) {
                continue; 
            }

            //Create unique filename
            $imageName = time() . "_" . uniqid() . "." . $ext;
            $targetFile = $targetDir . $imageName;

            //Move uploaded file
            if (move_uploaded_file($_FILES['propertyImage']['tmp_name'][$key], $targetFile)) {
                $imageNames[] = $imageName;
            }
        }
    }
}
$imageList = implode(',', $imageNames);

    //Handle document upload
    $uploadDirDocs = __DIR__ . "/include/documents/";
    $docNames = [];

if (!empty($_FILES['propertyDocs']['name'][0])) {
    if (!is_dir($uploadDirDocs)) {
        mkdir($uploadDirDocs, 0777, true);
    }

    foreach ($_FILES['propertyDocs']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['propertyDocs']['name'][$key]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $_FILES['propertyDocs']['size'][$key];

        $allowedDocs = ['pdf','doc','docx'];
        $maxSize = 5 * 1024 * 1024; 

        if (in_array($fileType, $allowedDocs) && $fileSize <= $maxSize) {
            $newFileName = time() . "_" . uniqid() . "_" . $fileName;
            $targetPath = $uploadDirDocs . $newFileName;

            if (move_uploaded_file($tmp_name, $targetPath)) {
                $docNames[] = $newFileName;
            } else {
                error_log("Failed to upload document: $fileName");
            }
        }
    }
}

$docsJson = json_encode($docNames);

    $property_price = floatval(str_replace(',', '', $_POST['propertyPrice']));    
    //insert to database    
    $status = 'Accepted';
    $user_status = 'active';

    $docList = $docsJson;

    $stmt = $conn->prepare("INSERT INTO properties 
    (client_name, client_mobile, email, property_title, property_price, property_type, listing_type, property_size, property_unit, full_address, property_address, property_images, property_documents, status, user_status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssss", 
    $clientName, 
    $clientMobile, 
    $client_email, 
    $propertyTitle, 
    $property_price, 
    $propertyType,
    $listingType, 
    $propertySize, 
    $propertyUnit,
    $propertyFulladd, 
    $propertyAddress, 
    $imageList, 
    $docList, 
    $status,
    $user_status
  );
    if ($stmt->execute()) {
        // Get inserted ID
        $lastId = $conn->insert_id;

        // Generate property code 
        $propertyCode = "PROP" . str_pad($lastId, 3, "0", STR_PAD_LEFT);

        // Update the record with generated property code
        $conn->query("UPDATE properties SET property_code = '$propertyCode' WHERE id = $lastId");

         $_SESSION['success_msg'] = "Property added successfully! Property Code: $propertyCode";

        header("Location: charts.php");
        exit();

    } else {
        echo "<pre>SQL ERROR: " . $stmt->error . "</pre>";
    }
    
    $stmt->close();
}
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
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Add Property</h3>
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
                  <a href="dashboard.php">Dashboard</a>
                </li>
                <li class="separator">
                  <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                  <a href="#">Add Property Details</a>
                </li>
              </ul>
            </div>
          
            <div class="card shadow p-4">
              <h4 class="mb-4">Add Property</h4>
              <form id="propertyForm" method="POST" enctype="multipart/form-data" novalidate>

      <!-- Client Name -->
      <div class="mb-3">
        <label class="form-label">Client Name</label>
        <input type="text" class="form-control" name="clientName" id="clientName" placeholder="Enter client name" required>
        <div class="text-danger small d-none" id="errClientName">Name must be at least 3 letters</div>
      </div>

      <!-- Client Mobile -->
      <div class="mb-3">
        <label class="form-label">Client Mobile Number</label>
        <input type="tel" class="form-control" id="clientMobile" name="clientMobile" pattern="[6-9]{1}[0-9]{9}" maxlength="10" placeholder="Enter 10-digit mobile number" required>
        <div class="text-danger small d-none" id="errClientMobile">Enter valid 10-digit mobile number</div>
      </div>

      <!--Client Email-->
      <div class="mb-3">
          <label for="clientEmail" class="form-label">Client Email</label>
          <input id="clientEmail" name="clientEmail" type="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Enter client email" required>
          <div class="invalid-feedback">Please enter a valid email address.</div>
        </div>

      <!-- Property Title -->
      <div class="mb-3">
        <label class="form-label">Property Title</label>
        <input type="text" class="form-control" id="propTitle" name="propertyTitle" placeholder="Enter property title" required>
        <div class="text-danger small d-none" id="errTitle">Title is required (min 3 letters)</div>
      </div>

      <!-- Property Images -->
      <div class="mb-3">
        <label class="form-label">Upload Property Images (Max 5)</label>
        <input type="file" class="form-control" id="propImages" name="propertyImage[]" accept="image/*" multiple required>
        <div class="text-danger small d-none" id="errImages">Please upload up to 5 images</div>
      </div>

      <!-- Property Documents -->
      <div class="mb-3">
        <label class="form-label">Upload Property Documents (PDF/DOC) </label>
        <input type="file" class="form-control" name="propertyDocs[]" id="propDocs" accept=".pdf,.doc,.docx" multiple required>
        <div class="form-text">
          Required: Title Deed, Sale Deed, Encumbrance Certificate, Property Tax Receipts, Building Plan, Occupancy Certificate, Patta (Any One)
        </div>
        <div class="text-danger small d-none" id="errDocs">Please upload required documents in PDF/DOC format</div>
      </div>

      <!--Property Price-->
      <div class="mb-3">
        <label for="propertyPrice" class="form-label">Property Price (₹)</label>
        <input id="propertyPrice" name="propertyPrice" type="number" class="form-control" placeholder="Enter property price" required>
        <div class="invalid-feedback">Please enter the property price.</div>
      </div>

    <!--Listing Type-->
    <div class="mb-3">
      <label class="form-label">Listing Type</label>
      <select class="form-select" name="listingType" id="listingType" required>
        <option value="">Select Listing Type</option>
        <option value="Buy"   <?php if (!empty($_POST['listingType']) && $_POST['listingType'] == 'Buy') echo 'selected'; ?>>Buy</option>
        <option value="Sell"  <?php if (!empty($_POST['listingType']) && $_POST['listingType'] == 'Sell') echo 'selected'; ?>>Sell</option>
        <option value="Rent"  <?php if (!empty($_POST['listingType']) && $_POST['listingType'] == 'Rent') echo 'selected'; ?>>Rent</option>
        <option value="Lease" <?php if (!empty($_POST['listingType']) && $_POST['listingType'] == 'Lease') echo 'selected'; ?>>Lease</option>
      </select>
      <div class="text-danger small d-none" id="errListingType">Please select a listing type</div>
    </div>

      <!-- Property Type -->
      <div class="mb-3">
        <label class="form-label">Property Type</label>
        <select class="form-select"  name="propertyType" id="propType" required>
          <option>Select Type</option>
          <option>Residential Plot</option>
          <option>Commercial Plot</option>
          <option>Commercial</option>
          <option>Condominium</option>
          <option>Agriculture Land</option>
          <option>Apartment</option>
          <option>Town House</option>
          <option>Flats</option>
          <option>House</option>
          <option>Villa</option>
          <option>Bungalow</option>
          <option>Farm Land</option>
          <option>Empty Land</option>
          <option>Industrial Land</option>
          <option>Rental Returns Property</option>
        </select>
        <div class="text-danger small d-none" id="errType">Please select a property type</div>
      </div>

      <!-- Property Measurement -->
      <div class="mb-3">
        <label class="form-label">Property Size</label>
        <div class="input-group">
          <input type="number" class="form-control" name="propertySize" id="propSize" placeholder="Enter size" required>

          <!--Property Unit-->
          <select class="form-select" id="propUnit" name="propertyUnit" required>
            <option value="">Select Unit</option>
            <option value="sqft">Square Feet</option>
            <option value="cent">Cent</option>
            <option value="acre">Acre</option>
          </select>
        </div>
        <div class="text-danger small d-none" id="errSize">Please enter size and unit</div>
      </div>

      <!-- Property Full Address -->
      <div class="mb-3">
        <label for="propertyFulladd" class="form-label">Property Full Address</label>
        <textarea id="propertyFulladd" name="propertyFulladd" class="form-control" rows="3" placeholder="Property full Address" required></textarea>
        <div class="invalid-feedback">Please enter the property full address.</div>
      </div>

      <!---Property Address-->
      <div class="mb-3">
        <label for="propertyAddress" class="form-label">Property Address</label>
        <textarea 
        id="propertyAddress" 
        name="propertyAddress" 
        class="form-control" 
        rows="2" 
        placeholder="Enter area, city, ZIP. Example: Madipakkam, Chennai - 600091" 
        maxlength="100" 
        required
        pattern="^[A-Za-z\s]+,\s*[A-Za-z\s]+ - \d{6}$"
        title="Please enter in format: Area, City - 600091"></textarea>
        <div class="invalid-feedback">
          Please enter only area, city, and ZIP in the format: Area, City - 600091
        </div>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn btn-primary w-100">Add Property</button>
    </form>
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
    <script>
      //validation
      const textarea = document.getElementById('propertyAddress');
      textarea.addEventListener('input', () => {
        const regex = /^[A-Za-z\s]+,\s*[A-Za-z\s]+ - \d{6}$/;
        if (!regex.test(textarea.value)) {
          textarea.classList.add('is-invalid');
          textarea.classList.remove('is-valid');
        } else {
          textarea.classList.remove('is-invalid');
          textarea.classList.add('is-valid');
        }});
      </script>
  </body>
</html>
