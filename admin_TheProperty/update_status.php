<?php
include 'include/db_connection.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'], $_POST['status'])) {
    $property_id = intval($_POST['property_id']);
    $new_status = trim($_POST['status']);

    // Determine user_status based on property status
    if ($new_status === 'accepted') {
        $user_status = 'active';
    } else {
        $user_status = 'inactive';
    }

    // Update both fields
    $stmt = $conn->prepare("UPDATE properties SET status = ?, user_status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $new_status, $user_status, $property_id);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: googlemaps.php?msg=success");
        exit();
    } else {
        echo "<script>alert('Error updating status.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>