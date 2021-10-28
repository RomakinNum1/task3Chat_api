<?php

use Workerman\Worker;

require_once __DIR__ . '/composer/vendor/autoload.php';

$wsWorker = new Worker('websocket://0.0.0.0:2346');
$wsWorker->count = 4;

$Connections = [];

$wsWorker->onConnect = function ($connection) {
    echo 'New connection
    ';
};

$wsWorker->onMessage = function ($connection, $data) use ($wsWorker) {
    $arrayData = json_decode($data);
    global $Connections;

    if ($arrayData->type === 'sendMessage') {
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'addUser') {
        echo $arrayData->name . '     ' . array_search($arrayData->name, $Connections) . '  \n';
        if (array_search($arrayData->name, $Connections)!==false) {
            $arrayData->usersArr = $Connections;
            $connection->send(json_encode($arrayData));
            return;
        }
        $Connections[] = $arrayData->name;
        $arrayData->usersArr = $Connections;

        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'removeUser') {
        unset($Connections[array_search($arrayData->name, $Connections)]);

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