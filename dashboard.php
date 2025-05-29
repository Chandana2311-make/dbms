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
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            color: #2c3e50;
        }

        p {
            font-size: 16px;
            color: #34495e;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
            box-shadow: 1px 1px 5px rgba(0,0,0,0.05);
        }

        a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout:hover {
            background-color: #c0392b;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
    <p>You are logged in as: <strong><?php echo ucfirst(htmlspecialchars($role)); ?></strong></p>

    <?php if ($role == 'admin') { ?>
        <div class="section">
            <h3>Admin Panel</h3>
            <ul>
                <li><a href="create_event.php">Create New Event</a></li>
                <li><a href="delete_event.php">Delete Events</a></li>
            </ul>
        </div>
    <?php } else { ?>
        <div class="section">
            <h3>Available Events</h3>
            <ul>
                <?php
                $sql = "SELECT * FROM events ORDER BY event_date ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($event = $result->fetch_assoc()) {
                        echo "<li>";
                        echo "<strong>" . htmlspecialchars($event['event_name']) . "</strong><br>";
                        echo "Description: " . htmlspecialchars($event['event_description']) . "<br>";
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
        </div>

        <div class="section">
            <h3 id="registered">My Registered Events</h3>
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
                        echo "</li>";
                    }
                } else {
                    echo "<li>You have not registered for any events.</li>";
                }
                ?>
            </ul>
        </div>
    <?php } ?>

    <a href="logout.php" class="logout">Logout</a>
</div>

</body>
</html>