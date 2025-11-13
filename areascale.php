<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>About The Realty Fills | Trusted Real Estate Experts</title>
  <meta name="description" content="Learn about The Realty Fills, your trusted partner for property for sale, property rental, land for sale, and real estate services.">
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

 
</head>

<body class="about-page">

<?php include './assets/include/nav.php'; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title">
      <div class="heading">
        <div class="container">
          <div class="row d-flex justify-content-center text-center">
            <div class="col-lg-8">
              <h1 class="heading-title">Realty Scale</h1>
              <p class="mb-0">
                Area scale refers to the measurement unit used to define the size of a property or land.
                It helps standardize area values, such as square feet, acres, or cents, for accurate comparison and listing.
              </p>
            </div>
          </div>
        </div>
      </div>
      <nav class="breadcrumbs">
        <div class="container">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">AreaScale</li>
          </ol>
        </div>
      </nav>
    </div><!-- End Page Title -->

<style>
  body {
    background-color: #f9fafb;
    font-family: 'Inter', sans-serif;
  }

  .converter-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    max-width: 520px;
    margin: 60px auto;
    padding: 2rem;
  }

  .swap-icon {
    position: absolute;
    top: 58%; /* Center between From and To */
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 2;
  }

  .swap-icon:hover {
    background: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
  }

  @media (max-width: 575px) {
    .converter-card {
      padding: 1.5rem;
    }
  }
  .swap-icon {
  position: absolute;
  top: 52%; /* Moved slightly upward */
  left: 50%;
  transform: translate(-50%, -50%);
  background: #fff;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 2;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.swap-icon:hover {
  background: #0d6efd;
  color: #fff;
  border-color: #0d6efd;
}


</style>
</head>
<body>

  <div class="converter-card">
  <h4 class="fw-bold mb-2">Area Converter</h4>
  <p class="text-muted mb-4">Enter the value and select desired unit</p>

  <!-- State -->
  <div class="mb-3">
    <select class="form-select" id="stateSelect">
      <option value="">Select State</option>
    </select>
    <small class="text-muted">
      <i class="bi bi-info-circle"></i> Different units of measurements.
    </small>
  </div>

  <!-- From + To container -->
  <div class="position-relative my-4" style="padding-bottom: 20px;">

    <!-- From -->
    <label class="form-label fw-semibold">From</label>
    <div class="input-group mb-4">
      <input type="number" id="fromValue" class="form-control" placeholder="Enter value">
      <select class="form-select" id="fromUnit">
        <option value="">Select unit</option>
      </select>
    </div>

    <!-- Swap icon (centered perfectly) -->
    <div class="swap-icon" id="swapUnits">
      <i class="bi bi-arrow-down-up fs-5"></i>
    </div>

    <!-- To -->
    <label class="form-label fw-semibold">To</label>
    <div class="input-group">
      <input type="number" id="toValue" class="form-control" placeholder="Converted value" readonly>
      <select class="form-select" id="toUnit">
        <option value="">Select unit</option>
      </select>
    </div>
  </div>
</div>

<style>
  
  .land-measurement-section {
    font-family: Arial, sans-serif;
    background: #fff;
    color: #333;
    max-width: 900px;
    margin: 60px auto;
    padding: 40px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    line-height: 1.6;
  }

  .land-measurement-section h1,
  .land-measurement-section h2,
  .land-measurement-section h3 {
    margin-top: 1.6em;
    margin-bottom: 0.6em;
    color: #0b3a80;
  }

  .land-measurement-section h1 {
    text-align: center;
    font-size: 2em;
    margin-bottom: 1em;
  }

  .land-measurement-section h2 {
    font-size: 1.4em;
    border-left: 4px solid #0b3a80;
    padding-left: 10px;
  }

  .land-measurement-section p {
    margin: 0.9em 0;
    text-align: justify;
  }

  .land-measurement-section ol.units-list {
    margin-left: 1.2em;
  }

  .land-measurement-section ol.units-list li {
    margin-bottom: 0.8em;
  }

  .land-measurement-section .table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5em 0;
  }

  .land-measurement-section .table th,
  .land-measurement-section .table td {
    border: 1px solid #ccc;
    padding: 10px 12px;
    text-align: left;
    vertical-align: top;
  }

  .land-measurement-section .table th {
    background-color: #0b3a80;
    color: #fff;
  }

  .land-measurement-section .table tr:nth-child(even) {
    background: #f2f2f2;
  }

  .land-measurement-section .table-title {
    margin-top: 2em;
    font-size: 1.2em;
    font-weight: bold;
  }

  .land-measurement-section .region-section h3 {
    margin-top: 1.8em;
    color: #198754;
  }

  .land-measurement-section .note {
    margin-top: 2em;
    padding: 15px;
    background: #e9f3ff;
    border-left: 4px solid #0b3a80;
  }

  @media (max-width: 600px) {
    .land-measurement-section {
      padding: 20px;
    }
    .land-measurement-section .table th,
    .land-measurement-section .table td {
      padding: 8px;
    }
  }
</style>

<div class="land-measurement-section">
  <h1>List of Land Measurement Units in India</h1>

  <ol class="units-list">
    <li><strong>Acre</strong><br>
      An acre is one of the most commonly used land measurement units globally. It is particularly significant in rural and agricultural land transactions. In India, one acre is equivalent to 4840 square yards or approximately 4046.86 square metres.</li>
    <li><strong>Hectare</strong><br>
      The hectare is a metric unit widely used to measure large tracts of land, especially in agriculture. One hectare equals 10,000 square metres or approximately 2.47 acres.</li>
    <li><strong>Bigha</strong><br>
      Bigha is a traditional unit of land measurement still used in many parts of India, especially in the northern states like Uttar Pradesh, Punjab, and Haryana.</li>
    <li><strong>Gaj</strong><br>
      The term “Gaj” is often used in North India as a substitute for “square yard”. One Gaj equals 9 square feet.</li>
    <li><strong>Square Metre</strong><br>
      A universal metric unit for measuring area, particularly useful for precise and small-scale measurements.</li>
    <li><strong>Square Foot</strong><br>
      One of the most commonly used units in urban India. 1 sq ft = 144 sq inches.</li>
    <li><strong>Square Yard</strong><br>
      Often used for plots and small properties in India. 1 sq yd = 9 sq ft.</li>
    <li><strong>Katha</strong><br>
      Regional unit used in Bihar, Jharkhand, and West Bengal. Value varies regionally.</li>
    <li><strong>Guntha</strong><br>
      Traditional unit used in Andhra Pradesh, Karnataka, and Maharashtra. 1 guntha = 1089 sq ft.</li>
    <li><strong>Biswa</strong><br>
      Used in Uttar Pradesh and Punjab. 1 biswa = around 48.4 sq yd (varies by region).</li>
    <li><strong>Cent</strong><br>
      Common in Tamil Nadu and Kerala. 1 cent = 435.6 sq ft.</li>
    <li><strong>Decimal</strong><br>
      Common in Eastern India. 1 decimal = 48.4 sq yd or 435.6 sq ft.</li>
  </ol>

  <div class="table-title">Common Land Measurement Table</div>
  <table class="table">
    <thead>
      <tr><th>Unit of Area</th><th>Conversion Unit</th></tr>
    </thead>
    <tbody>
      <tr><td>1 Square Feet (sq ft)</td><td>144 square inches</td></tr>
      <tr><td>1 Square Yard (sq yd)</td><td>9 sq ft</td></tr>
      <tr><td>1 Acre</td><td>4840 sq yd or 100.04 cents</td></tr>
      <tr><td>1 Hectare</td><td>10,000 sq m or 2.49 acres</td></tr>
      <tr><td>1 Square Kilometer (sq km)</td><td>247.1 acres</td></tr>
    </tbody>
  </table>

  <div class="table-title">Few Common Conversions</div>
  <table class="table">
    <thead><tr><th>Conversion</th><th>Value</th></tr></thead>
    <tbody>
      <tr><td>1 acre in bigha</td><td>1 Acre = 1.613 Bigha</td></tr>
      <tr><td>bigha to acre</td><td>1 Bigha = 0.619 acres</td></tr>
      <tr><td>1 hectare into bigha</td><td>1 Hectare = 3.953 Bigha</td></tr>
      <tr><td>1 bigha in square feet</td><td>1 Bigha = 27,000 sqft</td></tr>
      <tr><td>1 bigha in gaj</td><td>1 Bigha = 3057.68 gaj</td></tr>
    </tbody>
  </table>

  <div class="region-section">
    <h3>North India</h3>
    <table class="table">
      <thead><tr><th>Units</th><th>States / Region Info</th><th>Conversion</th></tr></thead>
      <tbody>
        <tr><td>Bigha</td><td>Himachal Pradesh, Uttarakhand</td><td>1210 sq yd</td></tr>
        <tr><td>Biswa</td><td>HP, Uttarakhand</td><td>48.4 sq yd</td></tr>
        <tr><td>Killa</td><td>Haryana, Punjab</td><td>4840 sq yd</td></tr>
        <tr><td>Kanal</td><td>Punjab, Haryana</td><td>5445 sqft (8 Kanals = 1 Acre)</td></tr>
      </tbody>
    </table>

    <h3>South India</h3>
    <table class="table">
      <thead><tr><th>Units</th><th>States</th><th>Conversion</th></tr></thead>
      <tbody>
        <tr><td>Ankanam</td><td>Andhra Pradesh, Karnataka</td><td>72 sqft</td></tr>
        <tr><td>Cent</td><td>Tamil Nadu, Kerala</td><td>435.6 sqft</td></tr>
        <tr><td>Ground</td><td>Tamil Nadu</td><td>2400 sqft</td></tr>
        <tr><td>Guntha</td><td>Andhra Pradesh, Karnataka</td><td>1089 sqft</td></tr>
      </tbody>
    </table>

    <h3>East India</h3>
    <table class="table">
      <thead><tr><th>Units</th><th>States</th><th>Conversion</th></tr></thead>
      <tbody>
        <tr><td>Chatak</td><td>West Bengal</td><td>180 sqft</td></tr>
        <tr><td>Decimal</td><td>West Bengal</td><td>48.4 sq yd</td></tr>
        <tr><td>Dhur</td><td>Bihar, Jharkhand</td><td>68.06 sqft</td></tr>
        <tr><td>Katha</td><td>Bihar</td><td>1361.25 sqft</td></tr>
      </tbody>
    </table>

    <h3>West India</h3>
    <table class="table">
      <thead><tr><th>Units</th><th>States</th><th>Conversion</th></tr></thead>
      <tbody>
        <tr><td>Bigha</td><td>Rajasthan</td><td>3025 sq yd</td></tr>
        <tr><td>Biswa</td><td>Rajasthan</td><td>151.25 sq yd</td></tr>
      </tbody>
    </table>

    <h3>Central India</h3>
    <table class="table">
      <thead><tr><th>Units</th><th>States</th><th>Conversion</th></tr></thead>
      <tbody>
        <tr><td>Bigha</td><td>Madhya Pradesh</td><td>1333.33 sq yd</td></tr>
        <tr><td>Katha</td><td>Madhya Pradesh</td><td>600 sq ft</td></tr>
      </tbody>
    </table>
  </div>

  <div class="note">
    <strong>Note:</strong> Different regions in India use different area units. Always verify conversions before finalising property transactions.
  </div>
</div>

  <script>
  //State-wise area units in India
  const stateUnits = {
    "Andhra Pradesh": ["Acre", "Cent", "Square Yard", "Square Feet"],
    "Tamil Nadu": ["Cent", "Acre", "Ground", "Square Feet"],
    "Kerala": ["Cent", "Acre", "Square Feet"],
    "Karnataka": ["Gunta", "Acre", "Square Feet", "Square Meter"],
    "Telangana": ["Gunta", "Acre", "Square Yard"],
    "Maharashtra": ["Guntha", "Acre", "Square Feet"],
    "Gujarat": ["Bigha", "Guntha", "Acre", "Square Yard"],
    "West Bengal": ["Bigha", "Katha", "Chatak", "Acre"],
    "Uttar Pradesh": ["Bigha", "Biswa", "Katha", "Acre"],
    "Bihar": ["Katha", "Bigha", "Dhur", "Acre"],
    "Punjab": ["Kanal", "Marla", "Acre"],
    "Delhi": ["Square Yard", "Acre", "Square Meter"],
    "Default": ["Acre", "Cent", "Square Feet", "Square Meter", "Hectare"]
  };

  //Conversion base
  const unitToSqFt = {
    "Square Feet": 1,
    "Square Yard": 9,
    "Square Meter": 10.7639,
    "Acre": 43560,
    "Cent": 435.6,
    "Ground": 2400,
    "Guntha": 1089,
    "Kanal": 5445,
    "Marla": 272.25,
    "Bigha": 27225,
    "Katha": 1361.25,
    "Biswa": 1361.25,
    "Chatak": 45,
    "Dhur": 68,
    "Gunta": 1089,
    "Hectare": 107639
  };

  const stateSelect = document.getElementById("stateSelect");
  const fromUnit = document.getElementById("fromUnit");
  const toUnit = document.getElementById("toUnit");
  const fromValue = document.getElementById("fromValue");
  const toValue = document.getElementById("toValue");

  //Populate state dropdown
  Object.keys(stateUnits).forEach(state => {
    const opt = document.createElement("option");
    opt.value = state;
    opt.textContent = state;
    stateSelect.appendChild(opt);
  });

  //Update units when state changes
  stateSelect.addEventListener("change", function () {
    const units = stateUnits[this.value] || stateUnits["Default"];
    fromUnit.innerHTML = '<option value="">Select unit</option>';
    toUnit.innerHTML = '<option value="">Select unit</option>';
    units.forEach(u => {
      fromUnit.innerHTML += `<option value="${u}">${u}</option>`;
      toUnit.innerHTML += `<option value="${u}">${u}</option>`;
    });
  });

  //Conversion logic
  function convertArea() {
    const val = parseFloat(fromValue.value);
    const from = fromUnit.value;
    const to = toUnit.value;
    if (isNaN(val) || !from || !to) {
      toValue.value = "";
      return;
    }
    if (!unitToSqFt[from] || !unitToSqFt[to]) {
      alert("Conversion not available for selected units");
      return;
    }
    const sqFt = val * unitToSqFt[from]; 
    const result = sqFt / unitToSqFt[to]; 
    toValue.value = result.toFixed(4);
  }

  fromValue.addEventListener("input", convertArea);
  fromUnit.addEventListener("change", convertArea);
  toUnit.addEventListener("change", convertArea);

  //Swap values
  document.getElementById("swapUnits").addEventListener("click", function () {
    [fromUnit.value, toUnit.value] = [toUnit.value, fromUnit.value];
    convertArea();
  });
</script>

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

</body>

</html>