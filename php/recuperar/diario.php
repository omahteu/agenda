<?php

require_once '../Database.php';
require_once '../Crud.php';

// Captura a variável 'instancia' da requisição GET
$instancia = isset($_GET['instancia']) ? intval($_GET['instancia']) : null;

if ($instancia === null) {
    // Retorna uma resposta de erro se a variável 'instancia' não for fornecida ou inválida
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Instância inválida ou não fornecida.']);
    exit;
}

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);


$query = "
    SELECT d.*, u.nome AS colaborador 
    FROM diario d
    JOIN usuarios u ON d.colaborador = u.id
    WHERE d.id = :instancia
";

// $query = "SELECT * FROM diario WHERE id = :instancia";

// Prepara e executa a consulta
$stmt = $db->prepare($query);
$stmt->bindParam(':instancia', $instancia, PDO::PARAM_INT);
$stmt->execute();

// Obtém os resultados
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($result);
?>
