<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $category = trim($_POST['category']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    // Define other necessary variables based on category
    $age = isset($_POST['age']) ? trim($_POST['age']) : null;
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : null;
    $role = 'user';
    $contact_number = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $specialization = isset($_POST['specialization']) ? trim($_POST['specialization']) : null;
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : null;
    $license_details = isset($_POST['license_details']) ? trim($_POST['license_details']) : null;
    $location_of_practice = isset($_POST['location_of_practice']) ? trim($_POST['location_of_practice']) : null;

    // Simple validation
    if (empty($username) || empty($email) || empty($password) || empty($category) || empty($longitude) || empty($latitude)) {
        echo "All fields are required.";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
        // Insert into the login table
        $stmt1 = $conn->prepare("INSERT INTO login (username, password, type) VALUES (?, ?, ?)");
        if ($stmt1 === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt1->bind_param("sss", $username, $hashed_password, $category);
        $stmt1->execute();

        if ($stmt1->affected_rows === 0) {
            throw new Exception("Failed to insert into login table.");
        }

        $login_id = $conn->insert_id;

        // Insert into the appropriate user table based on category
        if ($category === 'customer') {
            $stmt2 = $conn->prepare("INSERT INTO users (login_id, email, age, gender, role, contact_number) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("issssi", $login_id, $email, $age, $gender, $role, $contact_number);
        } elseif ($category === 'advocate') {
            $stmt2 = $conn->prepare("INSERT INTO lawyer (login_id, email, name, specialization, experience, license_details, location_of_practice) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("issssss", $login_id, $email, $name, $specialization, $experience, $license_details, $location_of_practice);
        } elseif ($category === 'agencies') {
            $stmt2 = $conn->prepare("INSERT INTO agencies (login_id, email) VALUES (?, ?)");
            $stmt2->bind_param("is", $login_id, $email);
        } else {
            throw new Exception("Invalid category.");
        }

        $stmt3 = $conn->prepare("INSERT INTO location (login_id, latitude, longitude, is_online) VALUES (?, ?, ?, ?)");
        $is_online = false;
        $stmt3->bind_param("issi", $login_id, $latitude, $longitude, $is_online);
        if ($stmt2 === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt2->execute();

        if ($stmt2->affected_rows === 0) {
            throw new Exception("Failed to insert into user table.");
        }
        if ($stmt3 === false) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt3->execute();

        if ($stmt3->affected_rows === 0) {
            throw new Exception("Failed to insert into location table.");
        }

        // Commit transaction
        $conn->commit();
        echo "Signup successful!";
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the statements and connection
        if (isset($stmt1)) {
            $stmt1->close();
        }
        if (isset($stmt2)) {
            $stmt2->close();
        }
        $conn->close();
    }
}
?>