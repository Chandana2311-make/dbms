<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT e.event_name, e.description, e.event_date, e.event_time 
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        WHERE r.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>My Registered Events</h2>";
if ($result->num_rows > 0) {
    while ($event = $result->fetch_assoc()) {
        echo "<div>";
        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong><br>";
        echo "Description: " . htmlspecialchars($event['description']) . "<br>";
        echo "Date: " . $event['event_date'] . "<br>";
        echo "Time: " . $event['event_time'] . "<br><br>";
        echo "</div>";
    }
} else {
    echo "You have not registered for any events.";
}
?>
