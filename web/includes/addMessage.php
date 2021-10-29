<?php

use Roman\Func\ConnectToDB;
use Roman\Func\DataBaseEditor;

const ERROR_ON_INPUTS = 1;

$dataBaseConnect = ConnectToDB::connect();                  //подключение к базе данных

DataBaseEditor::addMessage($dataBaseConnect, $_POST);       //функция добавления сообщения в базу данных