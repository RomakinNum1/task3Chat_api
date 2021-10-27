<?php

use Workerman\Worker;

require_once __DIR__.'/composer/vendor/autoload.php';

$wsWorker = new Worker('websocket://0.0.0.0:2346');
$wsWorker->count = 4;

global $Connections, $userId;

$wsWorker->onConnect = function ($connection){
    echo 'New connection
    ';
};

$wsWorker->onMessage = function ($connection, $data) use ($wsWorker) {
    $arrayData = json_decode($data);
    global $Connections, $userId;

    if ($arrayData->type === 'sendMessage') {
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'addUser') {
        if(isset($userId[$arrayData->name]))
        {
            $arrayData->usersArr = $Connections;
            $connection->send(json_encode($arrayData));
            return;
        }
        $Connections[] = $arrayData->name;
        $userId[$arrayData->name] = $arrayData->id;
        $arrayData->usersArr = $Connections;

        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'removeUser') {
        unset($Connections[array_search($arrayData->name, $Connections)]);
        unset($userId[$arrayData->name]);

        $arrayData->usersArr = $Connections;

        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }
};

$wsWorker->onClose = function ($connection) {
    echo 'Connection closed
    ';
};

Worker::runAll();