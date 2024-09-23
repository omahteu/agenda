<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Inicializa a query base com JOIN para trazer o nome do colaborador
$query = "
    SELECT d.*, u.nome as colaborador 
    FROM diario d
    JOIN usuarios u ON d.colaborador = u.id
    WHERE 1=1
";

// Array para armazenar os parâmetros
$params = [];

// Verifica se os parâmetros foram passados e os adiciona à query
if (isset($_GET['data']) && !empty($_GET['data'])) {
    $query .= " AND d.data = :data";
    $params[':data'] = $_GET['data'];
}

if (isset($_GET['colaborador']) && !empty($_GET['colaborador'])) {
    $query .= " AND d.colaborador = :colaborador";
    $params[':colaborador'] = $_GET['colaborador'];
}

if (isset($_GET['hospital']) && !empty($_GET['hospital'])) {
    $query .= " AND d.hospital = :hospital";
    $params[':hospital'] = $_GET['hospital'];
}

if (isset($_GET['material']) && !empty($_GET['material'])) {
    $query .= " AND d.material = :material";
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

// Formata o campo 'data' para o padrão brasileiro (dia/mês/ano)
foreach ($result as &$row) {
    if (isset($row['data'])) {
        $row['data'] = date('d/m/Y', strtotime($row['data']));
    }
}

header('Content-Type: application/json');
echo json_encode($result);

?>
