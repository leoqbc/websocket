<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    
    protected $clients;
    
    public function __construct()
    {
        
        $this->clients = new \SplObjectStorage();
        
    }
    
    public function onOpen(ConnectionInterface $conn)
    {
        
        $this->clients->attach($conn);
        echo "Nova conexao! ({$conn->resourceId})";
        
    }
    
    public function onMessage(ConnectionInterface $from, $msg)
    {
        
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
        
    }
    
    public function onClose(ConnectionInterface $conn) {
    
        $this->clients->detach($conn);
        echo "Conexao fechada por: id: {$conn->resourceId} \n";
        
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        
        echo "Oh shit! Erro encontrado, identificado como: {$e->getMessage()}\n";
        $conn->close();
        
    }
    
}