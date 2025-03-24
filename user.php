<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $role = 'user';
    $contact_number = trim($_POST['contactNumber']);
    $upiId = trim($_POST['upiID']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $login_id = trim($_POST['id']); // Changed variable name to login_id

    // File upload handling
    $image = $_FILES['image'];
    $file_name = null; // Initialize file_name as null

    // Simple validation
    if (empty($name) || empty($age) || empty($gender) || empty($role) || empty($contact_number) || empty($longitude) || empty($latitude) || empty($login_id) || empty($upiId)) {
        echo "All fields are required.";
        exit;
    }

    // Check if the file is a valid image and handle file upload
    if ($image['error'] == 0) {
        $check = getimagesize($image['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            exit;
        }

        $target_dir = "";
        $file_name = basename($image['name']);
        $target_file = $target_dir . uniqid() . "_" . $file_name; // Unique name for the file

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
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
        // Update user details
        if ($file_name) {
            $stmt2 = $conn->prepare("UPDATE users SET name = ?, age = ?, gender = ?, role = ?, upiId = ?, contact_number = ?, file_name = ? WHERE login_id = ?");
            $stmt2->bind_param("ssssssss", $name, $age, $gender, $role, $upiId, $contact_number, $target_file, $login_id);
        } else {
            // If no new file, exclude file_name from the update
            $stmt2 = $conn->prepare("UPDATE users SET name = ?, age = ?, gender = ?, role = ?, upiId = ?, contact_number = ? WHERE login_id = ?");
            $stmt2->bind_param("sssssss", $name, $age, $gender, $role, $upiId, $contact_number, $login_id);
        }
        $stmt2->execute();

        // Insert or update location
        $stmt3 = $conn->prepare("UPDATE location SET latitude = ?, longitude = ?, is_online = ? WHERE login_id = ?");
        $is_online = true; // Storing boolean value
        $stmt3->bind_param("ssii", $latitude, $longitude, $is_online, $login_id);
        $stmt3->execute();

        // Commit transaction
        $conn->commit();
        echo "Success";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the statements and connection
        if (isset($stmt2)) {
            $stmt2->close();
        }
        if (isset($stmt3)) {
            $stmt3->close();
        }
        $conn->close();
    }
}
?>