<?php
$conn = new mysqli("localhost", "root", "", "database");
if ($conn->connect_error) {
    echo json_encode(["status" => "ERROR"]);
    exit;
}

$query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($query);
$notifications = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = ["message" => $row['message'], "created_at" => $row['created_at']];
}

echo json_encode($notifications);
$conn->close();
?>
