<?php

use Roman\Func\ConnectToDB;
use Roman\Func\DataBaseEditor;
const ERROR_ON_INPUTS = 1;

$dataBaseConnect = ConnectToDB::connect();                                          //подключение к базе данных

echo json_encode(DataBaseEditor::getMessage($dataBaseConnect, $_POST['count']));    //функция получения сообщений из базы данных