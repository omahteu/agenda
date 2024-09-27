<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Recebe as variáveis 'data' e 'colaborador' via GET
$data = isset($_GET['data']) ? $_GET['data'] : null;
$colaborador = isset($_GET['colaborador']) ? $_GET['colaborador'] : null;

// Construção da query
$query = "
    SELECT u.nome as colaborador, d.horario_inicio, d.horario_fim, d.hospital 
    FROM diario d
    JOIN usuarios u ON d.colaborador = u.id
";

// Verifica se as variáveis 'data' e 'colaborador' possuem valor
$conditions = [];
if (!empty($data)) {
    $conditions[] = "d.data = :data";
}
if (!empty($colaborador)) {
    $conditions[] = "u.id = :colaborador";
}

// Adiciona as condições ao WHERE se houverem
if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $db->prepare($query);

// Bind dos parâmetros, caso existam
if (!empty($data)) {
    $stmt->bindParam(':data', $data);
}
if (!empty($colaborador)) {
    $stmt->bindParam(':colaborador', $colaborador);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
?>
