<?php

namespace Jc\EventManagement;

class Client {
    private $serverAddress;
    private $port;
    private $database;
    private $userId; // Store the current user's ID

    public function __construct($serverAddress, $port, Database $database, $userId) {
        $this->serverAddress = $serverAddress;
        $this->port = $port;
        $this->database = $database;
        $this->userId = $userId; // Store user ID on instantiation
    }

    public function connect() {
        echo "Connecting to server at {$this->serverAddress}:{$this->port}...\n";
    }

    public function sendMessage($message) {
        if ($this->isUserIdValid($this->userId)) {
            echo "User ID {$this->userId} is sending message: $message\n";
        } else {
            echo "User ID {$this->userId} is not valid.\n";
        }
    }

    public function receiveMessage() {
        echo "Receiving message from server for User ID {$this->userId}...\n";
    }

    // Validate the user ID
    public function isUserIdValid($userId = null) {
        // Use the current user ID if none is provided
        if ($userId === null) {
            $userId = $this->userId;
        }
        $event = new Event($this->database, null);
        return $event->isUserIdValid($userId);
    }

    // Getter for user ID
    public function getUserId() {
        return $this->userId;
    }
}