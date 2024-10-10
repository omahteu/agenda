<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$id = $_POST['colaborador']; // ID do usuário que será atualizado
$senha = $_POST['senha'];
$rsenha = $_POST['rsenha'];

// 1. Verifica se os campos obrigatórios estão vazios
if (empty($id) || empty($senha) || empty($rsenha)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'Todos os campos obrigatórios devem ser preenchidos.']);
    exit();
}

// 2. Verifica se as senhas são iguais
if ($senha !== $rsenha) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'As senhas não coincidem.']);
    exit();
}

// 3. Faz o hash da senha
$hashedSenha = password_hash($senha, PASSWORD_DEFAULT);

// Monta a consulta SQL para atualização
$query = "UPDATE usuarios SET
    senha = :senha
    WHERE id = :id";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':senha', $hashedSenha);
$stmt->bindParam(':id', $id);

// Executa a atualização
if ($stmt->execute()) {
    http_response_code(200); // Define o código de status HTTP para 200 (OK)
    echo json_encode(['message' => 'Senha atualizada com sucesso!']);
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    http_response_code(500); // Define o código de status HTTP para 500 (Erro interno no servidor)
    echo json_encode(['error' => $errorInfo[2]]);
}

