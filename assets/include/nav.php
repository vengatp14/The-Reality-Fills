
<!--user login-->
<?php
include '../TheProperty/admin_TheProperty/include/db_connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user record
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $email;
        header("Location: ../TheProperty/Profile.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login | The Realty Fills</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <style>
    input::placeholder,
    select,
    textarea::placeholder {
      color: #888 !important; 
      opacity: 1;
      font-size: 14px !important; 
      font-weight: 400 !important; 
}
    </style>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo.png" alt="">
        <!-- <h1 class="sitename">TheProperty</h1> -->
      </a>
      
      <nav id="navmenu" class="navmenu d-flex align-items-center justify-content-between w-100">
        <ul class="d-flex justify-content-center align-items-center flex-grow-1">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="properties.php">Properties</a></li>
          <li><a href="services.php">Services</a></li>
          <li><a href="areascale.php">AreaScale</a></li>
          <li><a href="jvco.php">JVCo</a></li>
          <li><a href="contact.php">Contact</a></li>
        <?php if (isset($_SESSION['user'])): ?>
      <li><a href="Profile.php">Profile</a></li>
    <?php endif; ?>
  </ul>

  <!--login & logout button -->
  <div>
    <?php if (isset($_SESSION['user'])): ?>
      <a href="logout.php" class="btn btn-getstarted">Logout</a>
    <?php else: ?>
      <button class="btn btn-getstarted" data-bs-toggle="modal" data-bs-target="#authModal">Login</button>
    <?php endif; ?>
  </div>
</nav>
    </div>
  </header>

  <!-- mobile nav  -->
   <!-- Bottom Navbar -->
<div class="bottom-nav">
  <div class="nav-item" data-link="index.php">
    <i class="bi bi-house-door-fill"></i>
    <span>Home</span>
  </div>
  <div class="nav-item" data-link="about.php">
    <i class="bi bi-info-circle-fill"></i>
    <span>About</span>
  </div>
  <div class="nav-item" data-link="properties.php">
    <i class="bi bi-building-fill"></i>
    <span>Properties</span>
  </div>
  <div class="nav-item" data-link="services.php">
    <i class="bi bi-briefcase-fill"></i>
    <span>Services</span>
  </div>
  <div class="nav-item" data-link="areascale.php">
    <i class="bi bi-info-circle-fill"></i>
    <span>AreaScale</span>
  </div>
  <div class="nav-item" data-link="jvco.php">
    <i class="bi bi-briefcase-fill"></i>
    <span>JVCo</span>
  </div>
  <div class="nav-item" data-link="contact.php">
    <i class="bi bi-telephone-fill"></i>
    <span>Contact</span>
  </div>
</div>

<!-- model for login/signup -->
<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        
        <!-- Login Form -->
         <form method="post" action="">
          <?php if ($error): ?>
            <div class="alert alert-danger text-center py-2"><?= $error ?></div>
            <?php endif; ?>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>

            <!-- Divider -->
             <div class="d-flex align-items-center my-3">
              <hr class="flex-grow-1">
              <span class="px-2 text-muted">or</span>
              <hr class="flex-grow-1">
            </div>
             <!-- Google Login -->
              <a href="assets/include/google_login.php" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-google me-2"></i> Login with Google
              </a>
            </form>

        <!-- Signup Form -->
        <form id="signupForm" method="post" action="../TheProperty/assets/include/signup.php" class="d-none">
          <div class="mb-3">
            <input type="text" class="form-control" name="name" id="signupName" placeholder="Enter Name" required>
            <small class="text-danger d-none" id="signupNameError"></small>
          </div>
          <div class="mb-3">
            <input type="tel" class="form-control" name="mobile" id="signupMobile" placeholder="Enter Mobile Number" pattern="[0-9]{10}" maxlength="10"  required oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            <small class="text-danger d-none" id="signupMobileError"></small>
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" name="email" id="signupEmail" placeholder="Enter Email" required>
            <small class="text-danger d-none" id="signupEmail"></small>
          </div>
          <div class="mb-3">
            <input type="password" class="form-control" name="password" id="signupPassword" placeholder="Enter Password" required>
            <small class="text-danger d-none" id="signupPassword"></small>
          </div>
          <div class="mb-3">
            <select class="form-select" name="usertype" id="signupUserType" required>
              <option value="">Select User Type</option>
              <option value="buyer">Buyer</option>
              <option value="seller">Seller</option>
              <option value="renter">Renter</option>
              <option value="lease">Lease</option>
            </select>
            <small class="text-danger d-none" id="signupUserTypeError"></small>
          </div>
          <button type="submit" name="signup" class="btn btn-danger w-100">Signup</button>
          <!-- Divider -->
             <div class="d-flex align-items-center my-3">
              <hr class="flex-grow-1">
              <span class="px-2 text-muted">or</span>
              <hr class="flex-grow-1">
            </div>
          <!-- Google Signup -->
             <a href="assets/include/google_login.php" class="btn btn-danger w-100 mb-3">
              <i class="bi bi-google me-2"></i> Signup with Google
            </a>
          </form>

      </div>
      <div class="modal-footer">
        <p class="w-100 text-center mb-0">
          <a href="#" id="toggleForm">Don’t have an account? Signup</a>
        </p>
      </div>
    </div>
  </div>
</div>

<!--toggle switch-->
<script>
  const toggle = document.getElementById("toggleForm");
  const modalTitle = document.getElementById("modalTitle");
  const loginForm = document.querySelector("form[action='']");
  const signupForm = document.getElementById("signupForm");

  toggle.addEventListener("click", (e) => {
    e.preventDefault();
    loginForm.classList.toggle("d-none");
    signupForm.classList.toggle("d-none");

    if (signupForm.classList.contains("d-none")) {
      modalTitle.textContent = "Login";
      toggle.textContent = "Don’t have an account? Signup";
    } else {
      modalTitle.textContent = "Signup";
      toggle.textContent = "Already have an account? Login";
    }
  });
  
  //signup switch
  document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('openLogin')) {
    const loginModal = new bootstrap.Modal(document.getElementById('authModal'));
    loginModal.show();
  }
});

//existing data
document.getElementById("signupEmail").addEventListener("blur", function() {
  let email = this.value.trim();
  if (email !== "") {
    fetch("assets/include/check_existing.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(data => {
      const error = document.getElementById("signupEmailError");
      if (data === "exists") {
        error.textContent = "This email is already registered.";
        error.classList.remove("d-none");
      } else {
        error.textContent = "";
        error.classList.add("d-none");
      }
    });
  }
});


document.getElementById("signupMobile").addEventListener("blur", function() {
  let mobile = this.value.trim();
  if (mobile !== "") {
    fetch("assets/include/check_existing.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "mobile=" + encodeURIComponent(mobile)
    })
    .then(response => response.text())
    .then(data => {
      const error = document.getElementById("signupMobileError");
      if (data === "exists") {
        error.textContent = "This mobile number is already registered.";
        error.classList.remove("d-none");
      } else {
        error.textContent = "";
        error.classList.add("d-none");
      }
    });
  }
});
</script>
