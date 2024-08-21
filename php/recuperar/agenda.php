<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Inicializa a query base
$query = "SELECT * FROM diario WHERE 1=1";

// Array para armazenar os parâmetros
$params = [];

// Verifica se os parâmetros foram passados e os adiciona à query
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $query .= " AND data = :data";
    $params[':data'] = $_GET['data'];
}

if (isset($_GET['colaborador']) && !empty($_GET['colaborador'])) {
    $query .= " AND colaborador = :colaborador";
    $params[':colaborador'] = $_GET['colaborador'];
}

if (isset($_GET['hospital']) && !empty($_GET['hospital'])) {
    $query .= " AND hospital = :hospital";
    $params[':hospital'] = $_GET['hospital'];
}

if (isset($_GET['material']) && !empty($_GET['material'])) {
    $query .= " AND material = :material";
    $params[':material'] = $_GET['material'];
}

// Prepara a consulta
$stmt = $db->prepare($query);

// Vincula os parâmetros se existirem
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

// Executa a consulta
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
?>
