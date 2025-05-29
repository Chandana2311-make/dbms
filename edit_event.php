<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$event_id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $location = trim($_POST['location']);

    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, event_date=?, location=? WHERE event_id=?");
    $stmt->bind_param("ssssi", $title, $description, $event_date, $location, $event_id);

    if ($stmt->execute()) {
        echo "Event updated successfully. <a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error updating event.";
    }
    exit;
} else {
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
</head>
<body>
    <h2>Edit Event</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

        <label>Date:</label><br>
        <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>" required><br><br>

        <label>Location:</label><br>
        <input type="text" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required><br><br>

        <button type="submit">Update Event</button>
    </form>
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>