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
        $texto = "Nova conexao! ({$conn->resourceId})";
        $qtdlogin = count($this->clients);
        foreach ($this->clients as $client) {
            $json = <<<JSON
            {
                "texto":"$texto",
                "qtdlogin":"$qtdlogin"
            }
JSON;
            $client->send($json);
        }
        
    }
    
    public function onMessage(ConnectionInterface $from, $msg)
    {
        
    #    $numRecv = count($this->clients) - 1;
    #    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
    #        , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $qtd = count($this->clients);
        foreach ($this->clients as $client) {
     #       if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                echo "Usuario $from->resourceId escreveu: " . $msg;
                $users = '"qtdlogin":"' . $qtd . '"';
                $client->send('{ "texto":"Usuario ' . $from->resourceId . ' escreveu: ' . $msg . '", ' . $users);
     #       }
        }
        
    }
    
    public function onClose(ConnectionInterface $conn) 
    {
        $conn->close();
        $this->clients->detach($conn);
        echo "Conexao fechada por: id: {$conn->resourceId} \n";
        $texto = "Conexao fechada por: id: {$conn->resourceId} \n";
        $qtdlogin = count($this->clients);
        foreach ($this->clients as $client) {
            $json = <<<JSON
            {
                "texto":"$texto",
                "qtdlogin":"$qtdlogin"
            }
JSON;
            echo $json;
            $client->send($json);
        }  
    }

    public function onError(ConnectionInterface $conn, \Exception $e) 
    {    
        echo "Oh shit! Erro encontrado, identificado como: {$e->getMessage()}\n{$e->getLine()}\n";
        $conn->close(); 
    }
    
}