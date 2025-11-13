<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../admin_TheProperty/include/db_connection.php';
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO users (name, mobile, email, password, usertype)
                VALUES ('$name', '$mobile', '$email', '$password', '$usertype')";
        if ($conn->query($sql)) {
            echo "<script>
            alert('Signup successful! Please login now.');
            window.location.href='../../index.php?openLogin=true';
            </script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    }
}

?>