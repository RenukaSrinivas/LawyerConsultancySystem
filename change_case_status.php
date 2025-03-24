<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $userId = $_POST['userId'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "database");

    if ($conn->connect_error) {
        echo json_encode(["status" => "ERROR", "message" => "Connection failed."]);
        exit;
    }

    // Fetch lawyer details
    $lawyerQuery = $conn->prepare("SELECT name, contact_number FROM lawyer WHERE login_id = ?");
    $lawyerQuery->bind_param("i", $userId);
    $lawyerQuery->execute();
    $lawyerResult = $lawyerQuery->get_result();
    $lawyer = $lawyerResult->fetch_assoc();
    $lawyerName = $lawyer['name'];
    $lawyerContact = $lawyer['contact_number'];
    $lawyerQuery->close();

    // Update case status
    $stmt = $conn->prepare("UPDATE problem_cases SET assigned_to = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssi", $userId, $action, $id);

    if ($stmt->execute()) {
        // Fetch login_id for notification
        $l = $conn->prepare("SELECT login_id FROM problem_cases WHERE id = ?");
        $l->bind_param("i", $id);
        $l->execute();
        $lr = $l->get_result();
        $lo = $lr->fetch_assoc();
        $login = $lo['login_id'];
        $l->close();

        // Prepare notification message
        $notification = "Case ID $id has been accepted by $lawyerName (Contact: $lawyerContact)";

        // Update user notification
        $notiStmt = $conn->prepare("UPDATE users SET notification = ? WHERE login_id = ?");
        $notiStmt->bind_param("si", $notification, $login);

        // Check if notification update was successful
        if ($notiStmt->execute()) {
            // Notification update success
            ?>
            <script>
                alert("Success");
            </script>
            <?php
        } else {
            // Notification update failure
            ?>
            <script>
                alert("Failed to update notification");
            </script>
            <?php
        }

        $notiStmt->close();
        echo json_encode(["status" => "SUCCESS", "message" => "Case accepted.", "notification" => $notification]);
    } else {
        // Case update failed
        echo json_encode(["status" => "ERROR", "message" => "Failed to update case status."]);
    }

    // Close statements and connection
    $stmt->close();
    $conn->close();
}
?>
