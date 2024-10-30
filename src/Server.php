<?php

class Server {
    private $host;
    private $port;
    private $socket;

    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
        $this->socket = null;
    }

    public function start() {
        // Code to start the server and listen for connections
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
        socket_bind($this->socket, $this->host, $this->port);
        socket_listen($this->socket);

        echo "Server started on {$this->host}:{$this->port}...\n";

        while (true) {
            $clientSocket = socket_accept($this->socket);
            $this->handleClient($clientSocket);
        }
    }

    private function handleClient($clientSocket) {
        // Code to handle client requests
        $request = socket_read($clientSocket, 1024);
        echo "Received request: $request\n";

        $response = "Hello Client!";
        socket_write($clientSocket, $response, strlen($response));
        socket_close($clientSocket);
    }

    public function stop() {
        // Code to stop the server
        if ($this->socket) {
            socket_close($this->socket);
        }
        echo "Server stopped.\n";
    }
}
