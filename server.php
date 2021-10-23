<?php

use Workerman\Worker;

require_once './composer/vendor/autoload.php';

$wsWorker = new Worker('websocket://0.0.0.0:2346');
$wsWorker->count = 4;

global $Connections;

$key = 0;

$wsWorker->onConnect = function ($connection) {
    echo 'New connection
    ';
};

$wsWorker->onMessage = function ($connection, $data) use ($wsWorker) {
    $arrayData = json_decode($data);
    if ($arrayData->type === 'sendMessage') {
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
    }
    if ($arrayData->type === 'addUser') {
        global $Connections;
        $Connections[] = $arrayData->name;
        $arrayData->usersArr = $Connections;
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
    }
    if ($arrayData->type === 'removeUser') {
        global $Connections;

        unset($Connections[array_search($arrayData->name, $Connections)]);

        $arrayData->usersArr = $Connections;
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
    }
};

$wsWorker->onClose = function ($connection) {
    echo 'Connection closed
    ';
};

Worker::runAll();