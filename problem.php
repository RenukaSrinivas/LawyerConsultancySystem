<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $contact_number = trim($_POST['contact_number']);
    $description = trim($_POST['description']);
    $login_id = trim($_POST['login_id']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    // File upload handling
    $image = $_FILES['image'];
    $file_name = basename($image['name']);
    $target_dir = "images/";
    $target_file = $target_dir . uniqid() . "_" . $file_name; // Unique name for the file

    // Simple validation
    if (empty($name) || empty($contact_number) || empty($login_id) || empty($description) || empty($longitude) || empty($latitude) || $image['error'] != 0) {
        echo "All fields are required.";
        exit;
    }

    // Check if the file is a valid image (you can add more validation as needed)
    $check = getimagesize($image['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        exit;
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($image['tmp_name'], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
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

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert into the problem_cases table
        $stmt1 = $conn->prepare("INSERT INTO problem_cases (name, description, file_name, contact_number, login_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt1 === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt1->bind_param("ssssi", $name, $description, $target_file, $contact_number, $login_id);
        $stmt1->execute();

        if ($stmt1->affected_rows === 0) {
            throw new Exception("Failed to insert into problem_cases table.");
        }

        // Commit transaction
        $conn->commit();
        echo "Case created successfully.";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the statements and connection
        if (isset($stmt1)) {
            $stmt1->close();
        }
        $conn->close();
    }
}
?>