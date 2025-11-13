<!--admin login-->
<?php
include '../admin_TheProperty/include/db_connection.php'; 
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check from admin table
    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: http://localhost/TheProperty/admin_TheProperty/dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login</title>

  

 <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />



</head>
<body >

<div class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow p-4" style="width: 350px;">
        <h1 class="text-center">Reality Fills</h1>
    <h4 class="text-center mb-4">Admin Login</h4>
    <form method="post" action="" id="loginForm" novalidate>
      <!-- Username -->
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
        <div class="text-danger small d-none" name="username" id="errUser">Username is required (min 3 letters)</div>
      </div>
      <!-- Password -->
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
        <div class="text-danger small d-none" name="password" id="errPass">Password is required (min 6 chars)</div>
      </div>
      <!-- Submit -->
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>

<script>
  const loginForm = document.getElementById("loginForm");
  const username = document.getElementById("username");
  const password = document.getElementById("password");

  const errUser = document.getElementById("errUser");
  const errPass = document.getElementById("errPass");

  loginForm.addEventListener("submit", (e) => {
    if (!valid) {
      e.preventDefault();
    }
    let valid = true;

    // Username validation
    if (username.value.trim().length < 3) {
      errUser.classList.remove("d-none");
      valid = false;
    } else {
      errUser.classList.add("d-none");
    }

    // Password validation
    if (password.value.trim().length < 6) {
      errPass.classList.remove("d-none");
      valid = false;
    } else {
      errPass.classList.add("d-none");
    }

    if (valid) {
      // alert
      alert("Login Successful! (Static Validation)");
    }
  });
</script>

</body>
</html>
