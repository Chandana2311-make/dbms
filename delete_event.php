<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$event_id = intval($_GET['id']);

// First, delete related registrations
$conn->query("DELETE FROM registrations WHERE event_id = $event_id");

// Then, delete the event
$conn->query("DELETE FROM events WHERE event_id = $event_id");

header("Location: dashboard.php");
exit;