<?php

use Roman\Func\ConnectToDB;
use Roman\Func\dataBaseEditor;
const ERROR_ON_INPUTS = 1;

$dataBaseConnect = ConnectToDB::connect();

echo json_encode(dataBaseEditor::getMessage($dataBaseConnect));