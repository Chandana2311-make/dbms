<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Optional: check if user is admin (if you use roles)
// Example: if ($_SESSION['role'] !== 'admin') { exit("Access denied"); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $event_date, $location);

    if ($stmt->execute()) {
        echo "Event added successfully. <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error adding event.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event</title>
</head>
<body>
    <h2>Add New Event</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Date:</label><br>
        <input type="date" name="event_date" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="location" required><br><br>

        <button type="submit">Add Event</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
