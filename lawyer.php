<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $experience = trim($_POST['experience']);
    $license = trim($_POST['license']);
    $contact_number = trim($_POST['contact_number']);
    $upiId = trim($_POST['upiId']);
    $location = trim($_POST['location']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $login_id = trim($_POST['id']);

    // File upload handling for document and QR code
    $document = $_FILES['document'];
    $qrCode = $_FILES['qrCode'];
    $document_file_name = null; // Initialize document file_name as null
    $qrCode_file_name = null; // Initialize QR code file_name as null

    // Simple validation
    if (empty($name) || empty($specialization) || empty($experience) || empty($license) || empty($location) || empty($contact_number) || empty($longitude) || empty($latitude) || empty($login_id)) {
        echo "All fields are required.";
        exit;
    }

    // Handle document file upload
    if ($document['error'] == 0) {
        $document_file_name = basename($document['name']);
        $target_document_file = "" . uniqid() . "_" . $document_file_name; // Unique name for the document file

        // Move the uploaded document to the target directory
        if (!move_uploaded_file($document['tmp_name'], $target_document_file)) {
            echo "Sorry, there was an error uploading your document.";
            exit;
        }
    }

    // Handle QR code file upload
    if ($qrCode['error'] == 0) {
        $qrCode_file_name = basename($qrCode['name']);
        $target_qr_code_file = "" . uniqid() . "_" . $qrCode_file_name; // Unique name for the QR code file

        // Move the uploaded QR code to the target directory
        if (!move_uploaded_file($qrCode['tmp_name'], $target_qr_code_file)) {
            echo "Sorry, there was an error uploading your QR code.";
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
        // Prepare the SQL statement
        $stmt2 = null;

        if ($document_file_name && $qrCode_file_name) {
            // Both files are uploaded
            $stmt2 = $conn->prepare("UPDATE lawyer SET name = ?, contact_number = ?, specialization = ?, experience = ?, license_details = ?, location_of_practice = ?, document_file = ?, qr_code_file = ?, upi_id = ? WHERE login_id = ?");
            $stmt2->bind_param("sssssssssi", $name, $contact_number, $specialization, $experience, $license, $location, $target_document_file, $target_qr_code_file, $upiId, $login_id);
        } elseif ($document_file_name) {
            // Only document file is uploaded
            $stmt2 = $conn->prepare("UPDATE lawyer SET name = ?, contact_number = ?, specialization = ?, experience = ?, license_details = ?, location_of_practice = ?, document_file = ?, upi_id = ? WHERE login_id = ?");
            $stmt2->bind_param("ssssssssi", $name, $contact_number, $specialization, $experience, $license, $location, $target_document_file, $upiId, $login_id);
        } elseif ($qrCode_file_name) {
            // Only QR code file is uploaded
            $stmt2 = $conn->prepare("UPDATE lawyer SET name = ?, contact_number = ?, specialization = ?, experience = ?, license_details = ?, location_of_practice = ?, qr_code_file = ?, upi_id = ? WHERE login_id = ?");
            $stmt2->bind_param("ssssssssi", $name, $contact_number, $specialization, $experience, $license, $location, $target_qr_code_file, $upiId, $login_id);
        } else {
            // No files uploaded, just update the other details
            $stmt2 = $conn->prepare("UPDATE lawyer SET name = ?, contact_number = ?, specialization = ?, experience = ?, license_details = ?, location_of_practice = ?, upi_id = ? WHERE login_id = ?");
            $stmt2->bind_param("sssssssi", $name, $contact_number, $specialization, $experience, $license, $location, $upiId, $login_id);
        }

        $stmt2->execute();

        // Update location
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