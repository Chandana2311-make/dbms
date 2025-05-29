<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($event_id > 0) {
    // Check if event exists
    $check_event = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $check_event->bind_param("i", $event_id);
    $check_event->execute();
    $event_result = $check_event->get_result();

    if ($event_result->num_rows > 0) {
        // Check if already registered
        $check_sql = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows === 0) {
            // Insert registration
            $insert_sql = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ii", $user_id, $event_id);
            $stmt->execute();
        }

        // Redirect to "My Registered Events"
        header("Location: dashboard.php#registered");
        exit;
    } else {
        echo "Invalid event ID.<br><br><a href='dashboard.php'>Back to Dashboard</a>";
    }
} else {
    echo "No event ID provided.<br><br><a href='dashboard.php'>Back to Dashboard</a>";
}
?>