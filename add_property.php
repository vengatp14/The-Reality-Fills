<?php
session_start();
include './admin_TheProperty/include/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $client_name = $_POST['clientName'] ?? '';
    $client_mobile = $_POST['clientMobile'] ?? '';
    $property_title = $_POST['propertyTitle'] ?? '';
    $property_price = $_POST['propertyPrice'] ?? '';
    $property_type = $_POST['propertyType'] ?? '';
    $property_size = $_POST['propertySize'] ?? '';
    $property_unit = $_POST['propertyUnit'] ?? '';
    $property_address = $_POST['propertyAddress'] ?? '';
    $user_email = $_SESSION['user'] ?? '';
    if (empty($user_email)) {
        die("Error: User not logged in.");
    }
    $status = 'Pending';

    //stored
    $imageDir = "TheProperty/admin_TheProperty/include/images/";
    $docDir = "TheProperty/admin_TheProperty/include/documents/";

    //Handle image uploads
    $image_paths = [];
    if (!empty($_FILES['propertyImages']['name'][0])) {
        foreach ($_FILES['propertyImages']['tmp_name'] as $key => $tmp_name) {
            $file_name = time() . '_' . basename($_FILES['propertyImages']['name'][$key]);
            $target_path = $imageDir . $file_name;

            if (move_uploaded_file($tmp_name, $target_path)) {
                
                $image_paths[] = 'TheProperty/admin_TheProperty/include/images/' . $file_name;
            }
        }
    }

    //Handle document uploads
    $document_paths = [];
    if (!empty($_FILES['propertyDocuments']['name'][0])) {
        foreach ($_FILES['propertyDocuments']['tmp_name'] as $key => $tmp_name) {
            $file_name = time() . '_' . basename($_FILES['propertyDocuments']['name'][$key]);
            $target_path = $docDir . $file_name;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $document_paths[] = 'TheProperty/admin_TheProperty/include/documents/' . $file_name;
            }
        }
    }

    //property code
    $property_code = "PROP" . date("Ymd") . rand(1000, 9999);
    $created_at = date("Y-m-d H:i:s");

    // Convert arrays to JSON
    $property_images = json_encode($image_paths);
    $property_documents = json_encode($document_paths);

    //Inserting
    $user_email = $_SESSION['user'];
    $propertyImagesJson = json_encode($image_paths); 
    $status = 'Pending'; 

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO properties 
    (property_code, client_name, client_mobile, property_title, property_images, property_documents, property_price, property_type, property_size, property_unit, property_address, created_at, status, email)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssssssssssss",
        $property_code,
        $client_name,
        $client_mobile,
        $property_title,
        $property_images,
        $property_documents,
        $property_price,
        $property_type,
        $property_size,
        $property_unit,
        $property_address,
        $created_at,
        $status,
        $user_email
    );
    if ($stmt->execute()) {
        echo "<script>
            alert('Property added successfully!');
            window.location.href = 'Profile.php';
        </script>";
    } else {
        echo "<script>alert('Error adding property: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>