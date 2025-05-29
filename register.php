<?php
session_start();
include 'db.php';

// Check if user is logged in and is participant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'participant') {
    echo "Access denied.";
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);

    // Check if already registered
    $stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "You have already registered for this event.<br><a href='dashboard.php'>Back to Dashboard</a>";
        exit;
    }

    // Register for event
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $event_id);

    if ($stmt->execute()) {
        echo "Successfully registered for the event!<br><a href='dashboard.php'>Back to Dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No event selected.<br><a href='dashboard.php'>Back to Dashboard</a>";
}
?>