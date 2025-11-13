<?php
include '../../admin_TheProperty/include/db_connection.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($query);
    echo ($result->num_rows > 0) ? "exists" : "ok";
}

if (isset($_POST['mobile'])) {
    $mobile = $_POST['mobile'];
    $query = "SELECT * FROM users WHERE mobile_number='$mobile'";
    $result = $conn->query($query);
    echo ($result->num_rows > 0) ? "exists" : "ok";
}
?>
