<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$id = intval($_POST['id']); // ID do usuário que será atualizado
$data = $_POST['data'];
$hospital = $_POST['hospital'];
$material = $_POST['material'];
$medico = $_POST['medico'];
$convenio = $_POST['convenio'];
$horario_inicio = $_POST['horario_inicio'];
$horario_fim = $_POST['horario_fim'];
$observacoes = $_POST['observacoes'];

// 1. Verifica se os campos obrigatórios estão vazios
if (empty($data) || empty($hospital) || empty($material) || empty($medico) || empty($convenio) || empty($horario_inicio) || empty($horario_fim)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
    exit();
}

// Monta a consulta SQL para atualização
$query = "UPDATE diario SET
    data = :data,
    hospital = :hospital,
    material = :material,
    medico = :medico,
    convenio = :convenio,
    horario_inicio = :horario_inicio,
    horario_fim = :horario_fim,
    observacoes = :observacoes
    WHERE id = :id";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':data', $data);
$stmt->bindParam(':hospital', $hospital);
$stmt->bindParam(':material', $material);
$stmt->bindParam(':medico', $medico);
$stmt->bindParam(':convenio', $convenio);
$stmt->bindParam(':horario_inicio', $horario_inicio);
$stmt->bindParam(':horario_fim', $horario_fim);
$stmt->bindParam(':observacoes', $observacoes);
$stmt->bindParam(':id', $id);

// Executa a atualização
if ($stmt->execute()) {
    http_response_code(200); // Define o código de status HTTP para 200 (OK)
    echo json_encode(['message' => 'Dados atualizados com sucesso!']);
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    http_response_code(500); // Define o código de status HTTP para 500 (Erro interno no servidor)
    echo json_encode(['error' => $errorInfo[2]]);
}

