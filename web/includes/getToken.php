<?php

use Roman\Func\ConnectToDB;
use Roman\Func\dataBaseEditor;

$dataBaseConnect = ConnectToDB::connect();

try {
    $res = dataBaseEditor::SelectForToken($dataBaseConnect, $_POST['token']);
    if ($res) {
        $response = [
            "status" => true,
            "login" => $res["login"],
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