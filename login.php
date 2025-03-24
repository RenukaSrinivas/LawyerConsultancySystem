<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $username = trim($_POST['email']);
    $password = trim($_POST['password']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);

    // Simple validation
    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }
    // Simple validation
    if (empty($longitude) || empty($latitude)) {
        echo json_encode(["success" => false, "message" => "Longitude and Latitude are required."]);
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
        die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
    }

    // Prepare statement to fetch user type and hashed password
    $stmt = $conn->prepare("SELECT l.password, l.type FROM login l WHERE l.username = ?");
    $stmt->bind_param("s", $username);

    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password, $category);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_username'] = $username;
            $_SESSION['user_category'] = $category;

            // Check if additional user details are needed based on the user type
            $userTable = '';
            switch ($category) {
                case 'customer':
                    $userTable = 'users';
                    break;
                case 'advocate':
                    $userTable = 'lawyer';
                    break;
                case 'agencies':
                    $userTable = 'agencies';
                    break;
                default:
                    echo json_encode(["success" => false, "message" => "Unknown user type."]);
                    exit;
            }

            // Fetch additional user information from the corresponding table
            $userStmt = $conn->prepare("SELECT * FROM $userTable WHERE login_id = (SELECT id FROM login WHERE username = ?)");
            $userStmt->bind_param("s", $username);
            $userStmt->execute();
            $userResult = $userStmt->get_result();

            if ($userResult->num_rows === 1) {
                $userData = $userResult->fetch_assoc();
                // Add user data to the session
                $_SESSION['user_data'] = $userData;

                // Update location with boolean for is_online
                $stmt = $conn->prepare("UPDATE location SET latitude = ?, longitude = ?, is_online = ? WHERE login_id = ?");
                $is_online = true; // Store as a boolean
                $stmt->bind_param("ssii", $latitude, $longitude, $is_online, $userData['login_id']);

                if (!$stmt->execute()) {
                    echo json_encode(["success" => false, "message" => "Failed to update location."]);
                    exit;
                }
            }

            // Return success response
            echo json_encode(["success" => true, "username" => $username, "category" => $category, "message" => "Login successful!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No user found with that username."]);
    }

    // Close the statements and connection
    $stmt->close();
    $conn->close();
}
?>