<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = trim($_POST['name']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $new_name, $new_password, $user_id);
    if ($stmt->execute()) {
        $_SESSION['name'] = $new_name;
        $message = "Profile updated successfully!";
    } else {
        $message = "Failed to update profile.";
    }
}

// Fetch user info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<h2>My Profile</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br><br>
    <label>New Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Update</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
