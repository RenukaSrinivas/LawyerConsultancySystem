<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $description = trim($_POST['complaintdescription']);
    $date = trim($_POST['complaintdate']);
    $type = trim($_POST['complainttype']);
    $contact = trim($_POST['complaintcontact']);
    $price = trim($_POST['complaintprice']);
    $login_id = trim($_POST['login_id']);

    // File upload handling
    $image = $_FILES['complaintimage'];
    $uploadDir = 'images/'; // Make sure this directory exists and is writable
    $uploadFile = $uploadDir . uniqid() . "_" . basename($image['name']);

    // Validate inputs
    if (empty($description) || empty($login_id) || empty($date) || empty($type) || empty($contact) || empty($price) || $image['error'] !== UPLOAD_ERR_OK) {
        echo "All fields are required.";
        exit;
    }

    // Move uploaded file to the desired directory
    if (!move_uploaded_file($image['tmp_name'], $uploadFile)) {
        echo "Error uploading file.";
        exit;
    }

    // Database connection
    $host = 'localhost';
    $db = 'database';
    $user = 'root';
    $pass = '';

    // Create connection
    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO complaints (description, date, type, contact_number, image, price, login_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $description, $date, $type, $contact, $uploadFile, $price, $login_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Submission successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>