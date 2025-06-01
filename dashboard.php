<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ece9e6, #ffffff);
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4a90e2;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2, h3 {
            color: #333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #f7f9fc;
            margin: 15px 0;
            padding: 15px;
            border-left: 5px solid #4a90e2;
            border-radius: 5px;
        }

        a {
            text-decoration: none;
            color: #4a90e2;
            font-weight: bold;
        }

        a:hover {
            color: #2c3e50;
        }

        .logout {
            text-align: center;
            margin-top: 30px;
        }

        .logout a {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout a:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<header>
     <h1>This is a test message</h1>
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
    <p>Your Event Dashboard</p>
</header>

<div class="container">

    <h3>Available Events</h3>
    <ul>
        <?php
        $sql = "SELECT * FROM events ORDER BY event_date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($event = $result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong><br>";
                echo "Date: " . htmlspecialchars($event['event_date']) . "<br>";
                echo "Time: " . htmlspecialchars($event['event_time']) . "<br>";
                echo "<a href='register_event.php?event_id=" . $event['event_id'] . "'>Register</a>";
                echo "</li>";
            }
        } else {
            echo "<li>No events available.</li>";
        }
        ?>
    </ul>

    <h3>My Registered Events</h3>
    <ul>
        <?php
        $sql = "SELECT e.event_name, e.event_date, e.event_time 
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
                echo "Date: " . htmlspecialchars($row['event_date']) . "<br>";
                echo "Time: " . htmlspecialchars($row['event_time']) . "<br>";
                echo "</li>";
            }
        } else {
            echo "<li>You have not registered for any events.</li>";
        }
        ?>
    </ul>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
