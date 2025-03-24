<?php
// Database connection
$host = 'localhost';
$db = 'database';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get latitude, longitude, and user ID from POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $your_latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) : 0;
    $your_longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) : 0;

    // Update the user's location
    $update_sql = "UPDATE location SET latitude = ?, longitude = ? WHERE login_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssi', $your_latitude, $your_longitude, $user_id);
    $update_stmt->execute();
    $update_stmt->close();

    // SQL query to find nearby users with a specific type
    $sql = "
     SELECT problem_cases.id AS case_id, problem_cases.name, problem_cases.description, problem_cases.contact_number, problem_cases.file_name
    FROM problem_cases
    JOIN login ON problem_cases.login_id = login.id
    WHERE login.type = ?
    AND problem_cases.assigned_to = ?
    AND problem_cases.status = 'solved'
    ORDER BY problem_cases.id
";

    $stmt = $conn->prepare($sql);
    $type = 'customer';
    $stmt->bind_param('ss', $type, $user_id);
    $stmt->execute();


    $result = $stmt->get_result();
    $users_with_distance = [];

    // Check if any users were found
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Append distance to user data
            $users_with_distance[] = $row; // Add user to the array
        }

        // Output the result as JSON
        header('Content-Type: application/json');
        echo json_encode($users_with_distance);
    } else {
        echo json_encode([]); // Return an empty array if no users found
    }

    $stmt->close();
}

$conn->close();
?>