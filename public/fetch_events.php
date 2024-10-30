<?php
// fetch_events.php
session_start();
require_once '../src/Database.php';
require_once '../src/Event.php';

use Jc\EventManagement\Database;
use Jc\EventManagement\Event;

// Initialize database connection
$database = new Database();
$dbConnection = $database->getConnection();

$eventList = new Event($database, null); // Pass null for user ID to fetch all events
$stmt = $eventList->read();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="event">';
    echo '<h2>' . htmlspecialchars($row['name']) . '</h2>';
    echo '<p><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($row['message']) . '</p>';
    echo '<p><strong>User ID:</strong> ' . htmlspecialchars($row['user_id']) . '</p>';
    echo '</div>';
}
