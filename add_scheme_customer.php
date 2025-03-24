<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $schemeId = trim($_POST['schemeId']);
    $userId = trim($_POST['userId']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $login_id = trim($_POST['id']);

    // Simple validation
    if (empty($userId) || empty($schemeId) || empty($longitude) || empty($latitude) || empty($login_id)) {
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
        $stmt2 = $conn->prepare("INSERT INTO customer_scheme (scheme_id, user_id, login_id) VALUES (?, ?, ?)");
        $stmt2->bind_param("sss", $schemeId, $userId, $login_id);

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