<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Query com JOIN para trazer o nome do colaborador
$query = "
    SELECT d.*, u.nome as colaborador 
    FROM diario d
    JOIN usuarios u ON d.colaborador = u.id
";

// Executa a query
$result = $crud->read($query);

// Converte o resultado para um array associativo
$data = json_decode($result, true);

// Formata o campo 'data' para o padrão brasileiro (dia/mês/ano)
foreach ($data as &$row) {
    if (isset($row['data'])) {
        $row['data'] = date('d/m/Y', strtotime($row['data']));
    }
}

header('Content-Type: application/json');
echo json_encode($data);

?>
