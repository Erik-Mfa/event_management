<?php
// Start a new session if needed
session_start();

// Database connection and class imports
require_once '../src/Database.php';
require_once '../src/Client.php';
require_once '../src/Event.php';

use Jc\EventManagement\Database;
use Jc\EventManagement\Client;
use Jc\EventManagement\Event;

// Initialize database connection
$database = new Database();
$dbConnection = $database->getConnection();

// Initialize client without a user ID for sending messages
$client = new Client('localhost', 8000, $database, null);
$client->connect();

// Handle AJAX form submission for event creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user ID before creating the event
    $userId = $_POST['user_id'];

    // Check if user ID is valid
    if (!$client->isUserIdValid($userId)) {
        echo json_encode(["success" => false, "message" => "User ID $userId is not valid."]);
        exit; // Exit to prevent further execution
    }

    // Create a new Event instance
    $event = new Event($database, $userId);
    $event->name = $_POST['name'];
    $message = $_POST['message'];

    // Create the event and echo a message
    if ($event->create($message)) {
        echo json_encode(["success" => true, "message" => "Event created successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Unable to create event."]);
    }
    exit; // Prevent further execution after handling AJAX request
}

// Fetch all events to display (this is only run on the initial page load)
$eventList = new Event($database, null); // Pass null for user ID to fetch all events
$stmt = $eventList->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Create Event</title>
    <h2>How to Use the App</h2>
    <ol>
        <li><strong>Fill in the Event Name:</strong> Enter a descriptive name for your event in the "Event Name" field.</li>
        <li><strong>Enter Your User ID:</strong> Provide your user ID in the "User ID" field. Ensure it is valid to create an event.</li>
        <li><strong>Add a Message:</strong> Write a message related to the event in the "Message" field. This could be additional details or context for the event.</li>
        <li><strong>Submit the Form:</strong> Click the "Create Event" button to submit your event. A success or error message will be displayed based on the outcome.</li>
        <li><strong>View Events:</strong> All created events will be displayed below the form. You can see the event name, date, message, and user ID associated with each event.</li>
    </ol>
    <style>
        /* Add some basic styling for better presentation */
        body {
            font-family: Arial, sans-serif;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .event {
            border: 1px solid #ccc;
            margin: 10px 0;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Create Event</h1>
    <form id="eventForm" action="index.php" method="POST">
        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="user_id">User ID:</label>
        <input type="text" id="user_id" name="user_id" required maxlength="20">

        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>

        <button type="submit">Create Event</button>
    </form>

    <div id="messageArea"></div> <!-- Place to display success/error messages -->

    <h1>Event List</h1>
    <div class="event-list">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="event">
                <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                <p><strong>Message:</strong> <?php echo htmlspecialchars($row['message']); ?></p>
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($row['user_id']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
    $(document).ready(function() {
        $('#eventForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission
            
            var formData = $(this).serialize(); // Serialize the form data
            
            $.ajax({
                type: 'POST',
                url: 'index.php', // Submit to the same page
                data: formData,
                success: function(response) {
                    $('#messageArea').html(response); // Display the response
                    $('#eventForm')[0].reset(); // Reset the form after submission
                    loadEvents(); // Load events to refresh the list
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX Error:', textStatus, errorThrown);
                    $('#messageArea').html("<p class='error'>Unable to create event. Error: " + textStatus + "</p>");
                }
            });
        });
    });

    // Function to load events
    function loadEvents() {
        $.ajax({
            url: 'fetch_events.php', // Create a separate PHP file to fetch events
            method: 'GET',
            success: function(data) {
                $('.event-list').html(data); // Update the event list
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching events:', textStatus, errorThrown);
            }
        });
    }
    </script>

</body>
</html>
