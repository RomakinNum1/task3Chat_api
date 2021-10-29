<?php

use Roman\Func\ConnectToDB;
use Roman\Func\DataBaseEditor;

$dataBaseConnect = ConnectToDB::connect();                                      //подключение к базе данных

try {
    $res = DataBaseEditor::SelectForToken($dataBaseConnect, $_POST['token']);   //получение данных из базы данных по токену(декодируется)
    if ($res) {
        $response = [                                                           //если запись найдена
            "status" => true,                                                   //возвращаем логин вместе со статусом
            "login" => $res["login"],                                           //логины уникальны
        ];

    } else {
        $response = [
            "status" => false
        ];
    }
    echo json_encode($response);
}
catch(Exception $exception)
{
    $response = [
        "status" => false
    ];
    echo json_encode($response);
}