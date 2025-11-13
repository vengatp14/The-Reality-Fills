<?php
session_start();
include './admin_TheProperty/include/db_connection.php';

// Ensure user logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    echo "<script>alert('Please log in to continue.'); window.location='login.php';</script>";
    exit;
}

$user_email = trim(strtolower($_SESSION['user'] ?? ''));

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // DELETE PROPERTY
    if (isset($_POST['property_id']) && !empty($_POST['property_id'])) {
        $property_id = $_POST['property_id'];

        $stmt = $conn->prepare("DELETE FROM properties WHERE id = ? AND email = ?");
        $stmt->bind_param("is", $property_id, $user_email);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
          echo "<script>alert('Property deleted successfully!'); window.location='Profile.php';</script>";
        } else {
          echo "<script>alert('Failed to delete property.'); window.location='Profile.php';</script>";
        }
        $stmt->close();

        header("Location: Profile.php");
        exit;
    }

    // ADD PROPERTY
    if (isset($_POST['clientName'])) {

        $clientName      = $_POST['clientName'] ?? '';
        $clientMobile    = $_POST['clientMobile'] ?? '';
        $clientEmail     = $_POST['clientEmail'] ?? '';
        $propertyTitle   = $_POST['propertyTitle'] ?? '';
        $listingType     = $_POST['listingType'] ?? '';
        $propertyType    = $_POST['propertyType'] ?? '';
        $propertySize    = $_POST['propertySize'] ?? '';
        $propertyUnit    = $_POST['propertyUnit'] ?? '';
        $propertyFulladd = $_POST['propertyFulladd'] ?? '';
        $propertyAddress = $_POST['propertyAddress'] ?? '';
        $propertyPrice   = $_POST['propertyPrice'] ?? '';

        $status = 'Pending';
        $userStatus = 'active';

        //IMAGE UPLOAD
        $uploadDirImages = './admin_TheProperty/include/images/';
        $imageNames = [];

        if (!empty($_FILES['propertyImages']['name'][0])) {
            foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($_FILES['propertyImages']['name'][$key]);
                $targetPath = $uploadDirImages . $fileName;
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $imageNames[] = $fileName;
                        if (count($imageNames) >= 5) {
                          break;
                        }
                    }
                }
            }
        }
        $imagesJson = json_encode($imageNames);

        //DOCUMENT UPLOAD
        $uploadDirDocs = './admin_TheProperty/include/documents/';
        $docNames = [];

        if (!empty($_FILES['propertyDocs']['name'][0])) {
            foreach ($_FILES['propertyDocs']['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($_FILES['propertyDocs']['name'][$key]);
                $targetPath = $uploadDirDocs . $fileName;
                $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedDocs = ['pdf', 'doc', 'docx'];

                if (in_array($fileType, $allowedDocs)) {
                    if (move_uploaded_file($tmp_name, $targetPath)) {
                        $docNames[] = $fileName;
                        if (count($docNames) >= 5) {
                          break;
                        }
                    }
                }
            }
        }
        $docsJson = json_encode($docNames);

        $user_email = trim(strtolower($_SESSION['user']));

        //INSERT INTO DATABASE
        $stmt = $conn->prepare("
            INSERT INTO properties 
            (client_name, client_mobile, property_title, listing_type, property_type, property_size, property_unit, full_address, property_address, property_price, property_images, property_documents, status, user_status, email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssssssssssss",
            $clientName,
            $clientMobile,
            $propertyTitle,
            $listingType,
            $propertyType,
            $propertySize,
            $propertyUnit,
            $propertyFulladd,
            $propertyAddress,
            $propertyPrice,
            $imagesJson,
            $docsJson,
            $status,
            $userStatus,
            $user_email
        );

        if ($stmt->execute()) {
            echo "<script>alert('Property added successfully!'); window.location='Profile.php';</script>";
        } else {
            echo "<script>alert('Error adding property. Please try again.'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    echo "<script>alert('Invalid form submission.'); window.history.back();</script>";
    exit;
} else {
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

 

    <!-- Property Request Form & Live Properties -->
    <section id="property-request" class="property-request section py-5">
      <div class="container" data-aos="fade-up">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="mb-3">Add Property Request</h3>
            <form action="Profile.php" method="POST" enctype="multipart/form-data" id="propertyRequestForm" class="php-email-form needs-validation" novalidate>
              <div class="mb-3">
                <label for="clientName" class="form-label">Client Name</label>
                <input id="clientName" name="clientName" type="text" class="form-control" placeholder="Enter client name" required>
                <div class="invalid-feedback">Please enter client name.</div>
              </div>

              <div class="mb-3">
                <label for="clientMobile" class="form-label">Client Mobile Number</label>
                <input id="clientMobile" name="clientMobile" type="tel" pattern="[0-9]{10}" class="form-control" placeholder="Enter 10-digit mobile number" required>
                <div class="form-text">Enter 10-digit mobile number (digits only).</div>
                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
              </div>

              <div class="mb-3">
                <label for="clientEmail" class="form-label">Client Email</label>
                <input id="clientEmail" name="clientEmail" type="email" class="form-control" placeholder="Enter client email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>

              <div class="mb-3">
                <label for="propertyTitle" class="form-label">Property Title</label>
                <input id="propertyTitle" name="propertyTitle" type="text" class="form-control" placeholder="Enter property title" required>
                <div class="invalid-feedback">Please enter a property title.</div>
              </div>

              <div class="mb-3">
                <label for="propertyImages" class="form-label">Upload Property Images (Max 5)</label>
                <input id="propertyImages" name="propertyImages[]" class="form-control" type="file" accept="image/*" multiple>
                <div class="form-text">You may upload up to 5 images. (Files are stored client-side for demo.)</div>
              </div>

              <div class="mb-3">
                <label for="propertyDocs" class="form-label">Upload Property Documents (PDF/DOC)</label>
                <input id="propertyDocs" name="propertyDocs[]" class="form-control" type="file" accept=".pdf,.doc,.docx" multiple>
                <div class="form-text">Required: Title Deed, Sale Deed, Encumbrance Certificate, Property Tax Receipts, Building Plan, Occupancy Certificate, Patta (Any One)</div>
              </div>

              <div class="mb-3">
                <label for="propertyPrice" class="form-label">Property Price (₹)</label>
                <input id="propertyPrice" name="propertyPrice" type="number" class="form-control" placeholder="Enter property price" required>
                <div class="invalid-feedback">Please enter the property price.</div>
              </div>

              <div class="mb-3">
                <label class="form-label">Listing Type</label>
                <select class="form-select" name="listingType" id="listingType" required>
                  <option value="">Select Listing Type</option>
                  <option value="Buy">Buy</option>
                  <option value="Sell">Sell</option>
                  <option value="Rent">Rent</option>
                  <option value="Lease">Lease</option>
                </select>
                <div class="text-danger small d-none" id="errListingType">Please select a listing type</div>
              </div>

              <div class="mb-3">
                <label for="propertyType" class="form-label">Property Type</label>
                <select id="propertyType" name="propertyType" class="form-select" required>
                  <option value="">Select Type</option>
                  <option>Apartment</option>
                  <option>Flat</option>
                  <option>House</option>
                  <option>Villa</option>
                  <option>Bungalow</option>
                  <option>Industrial Land</option>
                  <option>Agricultural Land</option>
                  <option>Farm Land</option>
                  <option>Empty Land</option>
                  <option>Industrial Land</option>
                  <option>Commercial Plot</option>
                  <option>Residential Plot</option>
                  <option>Commercial</option>
                  <option>Condominium</option>
                  <option>Rental Return Properties</option>
                </select>
                <div class="invalid-feedback">Please select a property type.</div>
              </div>

              <div class="row g-2 mb-3">
                <div class="col-7">
                  <label for="propertySize" class="form-label">Property Size</label>
                  <input id="propertySize" name="propertySize" type="text" class="form-control" placeholder="Enter size (number)" required>
                  <div class="invalid-feedback">Please enter the property size.</div>
                </div>
                <div class="col-5">
                  <label for="propertyUnit" class="form-label">Select Unit</label>
                  <select id="propertyUnit" name="propertyUnit" class="form-select" required>
                    <option value="">Select Unit</option>
                    <option>sqft</option>
                    <option>sq m</option>
                    <option>acre</option>
                    <option>hectare</option>
                  </select>
                  <div class="invalid-feedback">Please select a size unit.</div>
                </div>
              </div>

                <div class="mb-3">
                  <label for="propertyFulladd" class="form-label">Property Full Address</label>
                  <textarea id="propertyFulladd" name="propertyFulladd" class="form-control" rows="3" placeholder="Property full Address" required></textarea>
                  <div class="invalid-feedback">Please enter the property full address.</div>
                </div>

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

              <div class="d-grid">
                <button id="submitPropertyRequest" type="submit" class="btn btn-primary">Add Property Request</button>
              </div>
            </form>
          </div>

          <div class="col-lg-12">
            <h3 class="mb-3 mt-5">Live & Inactive Properties</h3>

            <div class="table-responsive">
              <table id="propertyRequestsTable" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Client</th>
                    <th>Mobile</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- rows injected by script -->
                  <?php
                  $filter_email = trim(strtolower($_POST['clientEmail'] ?? $_SESSION['user'] ?? ''));
                  if (empty($filter_email)) {
                    echo '<tr><td colspan="8" class="text-center text-danger">No email specified.</td></tr>';
                  } else {
                    $sql = "SELECT * FROM properties WHERE Lower(email) = ? AND user_status = 'active'";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $user_email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                  if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                        $images = json_decode($row['property_images'], true);
                        $image = 'assets/img/default-property.png';
                        if (!empty($images)) {
                          $imgFullPath = __DIR__ . '/admin_TheProperty/include/images/' . $images[0];
                          if (file_exists($imgFullPath)) {
                            $image = 'admin_TheProperty/include/images/' . $images[0];
                          }
                        }

                        // Status label
                        $status = strtolower(trim($row['status'] ?? 'pending'));
                        $badgeClass = ($status === 'accepted') ? 'bg-success' : (($status === 'pending') ? 'bg-warning text-dark' : 'bg-secondary');
                        $statusText = ($status === 'accepted') ? 'Active' : (($status === 'pending') ? 'Pending' : 'Inactive');
                        ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['client_mobile']); ?></td>
                    <td><?php echo htmlspecialchars($row['property_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['property_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['property_size'].' '.$row['property_unit']); ?></td>
                    <td><?php echo htmlspecialchars($row['property_address']); ?></td>
                    <td>
                      <?php
                      $status = strtolower(trim($row['status'] ?? 'pending')); 
                      $userStatus = strtolower(trim($row['user_status'] ?? 'inactive')); 
                      if ($status === 'pending') {
                        $badgeClass = 'bg-warning text-dark';
                        $statusText = 'Pending';
                      } elseif ($status === 'accepted') {
                        $badgeClass = 'bg-success';
                        $statusText = 'Active';
                      } else {
                        $badgeClass = 'bg-secondary';
                        $statusText = 'Inactive';
                      }
                      echo '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';
                      ?>
                    </td>
                    <td>
                        <form action="Profile.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this property?');">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php
            endwhile;
          else:
            ?>
            <tr>
              <td colspan="8" class="text-center text-muted">No properties found.</td>
            </tr>
            <?php
                endif;
            }
            ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Client</th>
                    <th>Mobile</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Size</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </tfoot>
              </table>
            </div>

          </div>
        </div>
      </div>
    </section>
    <!-- /Property Request Form & Live Properties -->

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
      
  <!--script>
  // Simple client-side storage and table UI for property requests (no backend).
  (function () {
    const form = document.getElementById('propertyRequestForm');
    const tableBody = document.querySelector('#propertyRequestsTable tbody');
    const STORAGE_KEY = 'realtyfills_property_requests_v1';

    // Seed dummy data when none exists (Live & Inactive sample entries)
    (function seedDummyData() {
      try {
        const existing = loadRequests();
        if (existing && existing.length) return;
        const now = new Date().toISOString();
        const seed = [
          {
            clientName: 'John Doe',
            clientMobile: '9876543210',
            propertyTitle: 'Luxury 3BHK Apartment',
            propertyType: 'Apartment',
            size: '1800',
            unit: 'sqft',
            propertyAddress: '123 Main Street, Mumbai',
            images: ['livingroom.jpg','exterior.jpg'],
            documents: ['title-deed.pdf'],
            createdAt: now,
            status: 'Live'
          },
          {
            clientName: 'Priya Sharma',
            clientMobile: '9123456789',
            propertyTitle: 'Commercial Office Space',
            propertyType: 'Commercial',
            size: '2500',
            unit: 'sqft',
            propertyAddress: '45 Business Park, Bengaluru',
            images: [],
            documents: ['sale-deed.pdf'],
            createdAt: now,
            status: 'Inactive'
          },
          {
            clientName: 'Ravi Kumar',
            clientMobile: '9988776655',
            propertyTitle: 'Residential Plot',
            propertyType: 'Land',
            size: '2400',
            unit: 'sqft',
            propertyAddress: 'Plot No. 12, Chennai',
            images: [],
            documents: ['patta.pdf'],
            createdAt: now,
            status: 'Live'
          },
          {
            clientName: 'Anjali Patel',
            clientMobile: '9001122334',
            propertyTitle: 'Independent Villa',
            propertyType: 'Villa',
            size: '3200',
            unit: 'sqft',
            propertyAddress: 'Sunshine Colony, Pune',
            images: ['garden.jpg'],
            documents: [],
            createdAt: now,
            status: 'Inactive'
          },
          {
            clientName: 'Mohit Singh',
            clientMobile: '9876501234',
            propertyTitle: 'Retail Shop',
            propertyType: 'Commercial',
            size: '600',
            unit: 'sqft',
            propertyAddress: 'Market Road, Delhi',
            images: [],
            documents: ['occupancy-certificate.pdf'],
            createdAt: now,
            status: 'Live'
          }
        ];
        saveRequests(seed);
      } catch (e) {
        // fail silently - keep app usable
      }
    })();

    // Utility: load stored requests
    function loadRequests() {
      try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : [];
      } catch (e) {
        return [];
      }
    }

    // Utility: save requests
    function saveRequests(list) {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    }

    // Render table rows
    function renderTable() {
      const list = loadRequests();
      tableBody.innerHTML = '';
      if (!list.length) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="8" class="text-center text-muted">No property requests yet.</td>';
        tableBody.appendChild(tr);
        return;
      }
      list.forEach((item, idx) => {
        const tr = document.createElement('tr');
        const sizeText = item.size ? (item.size + ' ' + (item.unit || '')) : '';
        tr.innerHTML = `
          <td>${escapeHtml(item.clientName)}</td>
          <td>${escapeHtml(item.clientMobile)}</td>
          <td>${escapeHtml(item.propertyTitle)}</td>
          <td>${escapeHtml(item.propertyType)}</td>
          <td>${escapeHtml(sizeText)}</td>
          <td>${escapeHtml(item.propertyAddress)}</td>
          <td><span class="badge ${item.status === 'Live' ? 'bg-success' : 'bg-secondary'}">${escapeHtml(item.status)}</span></td>
          <td>
            <div class="btn-group" role="group" aria-label="actions">
              <button class="btn btn-sm btn-outline-danger delete-btn" data-idx="${idx}">Delete</button>
            </div>
          </td>`;
        tableBody.appendChild(tr);
      });
      attachRowHandlers();
    }

    // Escape HTML to prevent XSS
    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    // Attach click handlers on table action buttons
    function attachRowHandlers() {
      document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.onclick = function () {
          const idx = Number(this.getAttribute('data-idx'));
          const list = loadRequests();
          if (!list[idx]) return;
          list[idx].status = list[idx].status === 'Live' ? 'Inactive' : 'Live';
          saveRequests(list);
          renderTable();
        };
      });
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.onclick = function () {
          if (!confirm('Delete this property request?')) return;
          const idx = Number(this.getAttribute('data-idx'));
          const list = loadRequests();
          list.splice(idx, 1);
          saveRequests(list);
          renderTable();
        };
      });
    }

    // Form validation (Bootstrap)
    function bsValidate(formEl) {
      if (!formEl) return false;
      if (!formEl.checkValidity()) {
        formEl.classList.add('was-validated');
        return false;
      }
      return true;
    }

    // On submit - collect data and store
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!bsValidate(form)) return;

        const data = {
          clientName: form.clientName.value.trim(),
          clientMobile: form.clientMobile.value.trim(),
          propertyTitle: form.propertyTitle.value.trim(),
          propertyType: form.propertyType.value,
          size: form.propertySize.value.trim(),
          unit: form.propertyUnit.value,
          propertyAddress: form.propertyAddress.value.trim(),
          createdAt: new Date().toISOString(),
          status: 'Inactive' // default to Inactive; toggle to Live via actions
        };

        // For demo: store minimal file metadata (names) if files selected
        const imgFiles = document.getElementById('propertyImages').files;
        if (imgFiles && imgFiles.length) {
          data.images = Array.from(imgFiles).slice(0,5).map(f => f.name);
        }
        const docFiles = document.getElementById('propertyDocs').files;
        if (docFiles && docFiles.length) {
          data.documents = Array.from(docFiles).map(f => f.name);
        }

        const list = loadRequests();
        list.unshift(data); // newest first
        saveRequests(list);
        renderTable();
        form.reset();
        form.classList.remove('was-validated');
      });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
      renderTable();
    });

  })();
</script-->

</body>

</html>