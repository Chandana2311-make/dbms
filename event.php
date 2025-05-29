<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "<h2>Welcome, " . htmlspecialchars($_SESSION['name']) . "</h2>";
echo "<h3>Available Events</h3>";

$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($event = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong><br>";
        echo "Date: " . $event['event_date'] . "<br>";
        echo "Time: " . $event['event_time'] . "<br>";
        echo "<a href='register_event.php?event_id=" . $event['event_id'] . "'>Register</a>";
        echo "</li><br>";
    }
    echo "</ul>";
} else {
    echo "No events available.";
}
?>

<br>
<a href="logout.php">Logout</a>