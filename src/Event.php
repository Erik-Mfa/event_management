<?php
namespace Jc\EventManagement;

class Event {
    private $database;
    public $name;
    public $user_id;


    public function __construct(Database $database, $userId) {
        $this->database = $database; // Store the database connection
        $this->user_id = $userId; // Store user ID
    }

    public function create($message) {
        $query = "INSERT INTO events (name, date, message, user_id) VALUES (:name, NOW(), :message, :user_id)";
        $stmt = $this->database->getConnection()->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if (!$stmt->execute()) {
            // Output error details if execution fails
            $errorInfo = $stmt->errorInfo(); // Get error info
            echo "SQL Error: " . htmlspecialchars($errorInfo[2]); // Output the error
            return false; // Indicate failure
        }
        
        return true; // Indicate success
    }
    

    public function read() {
        if ($this->user_id === null) {
            $query = "SELECT * FROM events"; // Fetch all events if user_id is null
        } else {
            $query = "SELECT * FROM events WHERE user_id = :user_id";
        }
        
        $stmt = $this->database->getConnection()->prepare($query);
        
        if ($this->user_id !== null) {
            $stmt->bindParam(':user_id', $this->user_id);
        }
        
        $stmt->execute();
        
        // Debugging: Check if any events are fetched
        if ($stmt->rowCount() == 0) {
            echo "No events found for user ID: " . htmlspecialchars($this->user_id);
        }
        
        return $stmt;
    }

    public function isUserIdValid($userId = null) {
        if ($userId === null) {
            $userId = $this->user_id; // Use $this->user_id
        }
    
        // Check the correct column name based on your database schema
        $query = "SELECT COUNT(*) FROM users WHERE UserId = :user_id"; // Update with the correct column name
        $stmt = $this->database->getConnection()->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    
        // Return true if user exists
        return $stmt->fetchColumn() > 0;
    }
    
    
}
