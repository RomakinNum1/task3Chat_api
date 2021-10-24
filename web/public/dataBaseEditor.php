<?php

namespace Roman\Func;

use Firebase\JWT\JWT;
use PDO;

class dataBaseEditor
{
    static function SelectForToken($connect, $data)
    {
        $decoded = JWT::decode($data, $_ENV['JWT_KEY'], array('HS256'));
        $checkLogin = $connect->prepare('SELECT * FROM users WHERE id = :id');
        $checkLogin->execute(['id' => $decoded->id]);

        return $checkLogin->fetch(PDO::FETCH_ASSOC);
    }

    static function addMessage($dataBaseConnect, $data)
    {
        $resultDB = $dataBaseConnect->prepare("insert into messages values (null, :name, :message)");
        $resultDB->execute($data);
    }

    static function getMessage($dataBaseConnect): array
    {
        $resultDB = $dataBaseConnect->prepare("SELECT * FROM messages");
        $resultDB->execute();

        $output = [];
        while ($row = $resultDB->fetch(PDO::FETCH_ASSOC)) {
            $output[] = $row;
        }
        return $output;
    }
}