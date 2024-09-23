<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

$query = "SELECT colaborador, horario_inicio, horario_fim, hospital FROM diario";
$result = $crud->read($query);

header('Content-Type: application/json');
echo $result;
?>
