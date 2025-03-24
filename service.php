<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $contact_information = trim($_POST['contact_information']);
    $service_provider = trim($_POST['service_provider']);
    $area = trim($_POST['area']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $login_id = trim($_POST['id']);

    // Simple validation
    if (empty($name) || empty($contact_information) || empty($service_provider) || empty($longitude) || empty($latitude) || empty($login_id) || empty($area)) {
        echo "All fields are required.";
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
        // Update user details
        $stmt2 = $conn->prepare("UPDATE agencies SET name = ?, contact_information = ?, service_provider = ?, area = ? WHERE login_id = ?");
        $stmt2->bind_param("ssssi", $name, $contact_information, $service_provider, $area, $login_id);
        $stmt2->execute();

        // Insert location
        $stmt3 = $conn->prepare("UPDATE location SET  latitude = ?, longitude = ?, is_online = ? WHERE login_id = ?");
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