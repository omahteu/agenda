<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$id = "1"; // ID do usuário que será atualizado
$inicio = $_POST['tipo_inicio_gps'];
$fim = $_POST['tipo_fim_gps'];
$hora_inicio = $_POST['hora-inicio-contagem'];
$hora_fim = $_POST['hora-fim-contagem'];



// 1. Verifica se os campos obrigatórios estão vazios
if (empty($inicio) || empty($fim) || empty($hora_inicio) || empty($hora_fim)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
    exit();
}

// Monta a consulta SQL para atualização
$query = "UPDATE ponto SET
    inicio = :inicio,
    hora_inicio = :hora_inicio,
    fim = :fim,
    hora_fim = :hora_fim
    WHERE id = :id";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':inicio', $inicio);
$stmt->bindParam(':hora_inicio', $hora_inicio);
$stmt->bindParam(':fim', $fim);
$stmt->bindParam(':hora_fim', $hora_fim);
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

