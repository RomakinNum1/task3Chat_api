<?php

use Workerman\Worker;

require_once __DIR__ . '/composer/vendor/autoload.php';

$wsWorker = new Worker($_ENV['SERVER_CONNECTION_URL']);                     //создание сервера
//$wsWorker = new Worker('websocket://0.0.0.0:2346');
$wsWorker->count = 4;

$Connections = [];                                                          //массив подключённых пользователей(хранит логины)
$CountOfUserConnection = [];                                                //массив количества подключений пользователя

$wsWorker->onConnect = function ($connection) {                             //функция нового подключения
    echo 'New connection
    ';
};

$wsWorker->onMessage = function ($connection, $data) use ($wsWorker) {      //функция получения сообщения от клиента
    $arrayData = json_decode($data);
    global $Connections, $CountOfUserConnection;

    if ($arrayData->type === 'sendMessage') {                               //обработка отправки сообщения
        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'addUser') {                                   //обработка нового подключения
        echo $arrayData->name . '     ' . array_search($arrayData->name, $Connections) . '  \n';
        if (array_search($arrayData->name, $Connections)!==false) {
            $arrayData->usersArr = $Connections;
            $connection->send(json_encode($arrayData));
            $CountOfUserConnection[$arrayData->name]++;
            return;
        }
        $Connections[] = $arrayData->name;
        $CountOfUserConnection[$arrayData->name] = 1;
        $arrayData->usersArr = $Connections;

        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }

    if ($arrayData->type === 'removeUser') {                                //обработка отключения клиента
        $CountOfUserConnection[$arrayData->name]--;
        if($CountOfUserConnection[$arrayData->name] !== 0) return;

        unset($Connections[array_search($arrayData->name, $Connections)]);

        $arrayData->usersArr = $Connections;

        foreach ($wsWorker->connections as $clientConn) {
            $clientConn->send(json_encode($arrayData));
        }
        return;
    }
};

$wsWorker->onClose = function ($connection) {                               //функция отключения
    echo 'Connection closed
    ';
};

Worker::runAll();                                                           //запуск сервера