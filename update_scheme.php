<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $name = trim($_POST['name']);
    $amount = trim($_POST['amount']);
    $period = trim($_POST['period']);
    $coverage = trim($_POST['coverage']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $id = trim($_POST['id']);
    $login_id = trim($_POST['login_id']);

    // Simple validation
    if (empty($name) || empty($amount) || empty($period) || empty($coverage) || empty($longitude) || empty($latitude) || empty($login_id) || empty($id)) {
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
        // Prepare the SQL statement for INSERT
        $stmt2 = $conn->prepare("UPDATE scheme SET name = ?, amount = ?, period = ?, coverage = ? WHERE id = ?");
        $stmt2->bind_param("sssss", $name, $amount, $period, $coverage, $id);

        $stmt2->execute();

        // Update location
        $stmt3 = $conn->prepare("UPDATE location SET latitude = ?, longitude = ?, is_online = ? WHERE login_id = ?");
        $is_online = true; // Storing boolean value
        $stmt3->bind_param("ssis", $latitude, $longitude, $is_online, $login_id);
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