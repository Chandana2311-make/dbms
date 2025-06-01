<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user details from session
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>

<h3>Available Events</h3>
<ul>
    <?php
    $sql = "SELECT * FROM events ORDER BY event_date ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($event = $result->fetch_assoc()) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong><br>";
            echo "Description: " . htmlspecialchars($event['description']) . "<br>";
            echo "Date: " . htmlspecialchars($event['event_date']) . "<br>";
            echo "Time: " . htmlspecialchars($event['event_time']) . "<br>";
            echo "<a href='register_event.php?event_id=" . $event['event_id'] . "'>Register</a>";
            echo "</li><br>";
        }
    } else {
        echo "<li>No events available.</li>";
    }
    ?>
</ul>

<h3>My Registered Events</h3>
<ul>
    <?php
    $sql = "SELECT e.event_name, e.description, e.event_date, e.event_time 
            FROM events e
            INNER JOIN registrations r ON e.event_id = r.event_id
            WHERE r.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>";
            echo "<strong>" . htmlspecialchars($row['event_name']) . "</strong><br>";
            echo "Description: " . htmlspecialchars($row['description']) . "<br>";
            echo "Date: " . htmlspecialchars($row['event_date']) . "<br>";
            echo "Time: " . htmlspecialchars($row['event_time']) . "<br>";
            echo "</li><br>";
        }
    } else {
        echo "<li>You have not registered for any events.</li>";
    }
    ?>
</ul>

<br>
<a href="logout.php">Logout</a>

</body>
</html>
