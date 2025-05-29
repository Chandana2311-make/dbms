<?php
session_start();
require 'db.php';

// Only allow admins to create events (optional check)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['location']; // because your DB uses 'event_time'

    $stmt = $conn->prepare("INSERT INTO events (event_name, description, event_date, event_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $event_name, $description, $event_date, $event_time);

    if ($stmt->execute()) {
        echo "Event created successfully. <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error creating event: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>
    <h2>Create Event</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>
        
        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>
        
        <label>Date:</label><br>
        <input type="date" name="event_date" required><br><br>
        
        <label>Time:</label><br>
        <input type="time" name="location" required><br><br>
        
        <button type="submit">Create Event</button>
    </form>
</body>
</html>