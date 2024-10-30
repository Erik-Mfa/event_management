<?php
namespace Jc\EventManagement;

class Event {
    private $database;
    public $name;
    public $user_id;


    public function __construct(Database $database, $userId) {
        $this->database = $database; 
        $this->user_id = $userId; 
    }

    public function create($message) {
        $query = "INSERT INTO events (name, date, message, user_id) VALUES (:name, NOW(), :message, :user_id)";
        $stmt = $this->database->getConnection()->prepare($query);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo(); 
            echo "SQL Error: " . htmlspecialchars($errorInfo[2]); // Output the error
            return false; 
        }
        
        return true;
    }
    

    public function read() {
        if ($this->user_id === null) {
            $query = "SELECT * FROM events"; 
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
            $userId = $this->user_id; 
        }
    
        $query = "SELECT COUNT(*) FROM users WHERE UserId = :user_id"; 
        $stmt = $this->database->getConnection()->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    
        // Return true if user exists
        return $stmt->fetchColumn() > 0;
    }
    
    
}
