<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$dataNascimento = $_POST["nascimento"];
$telefone = $_POST["telefone"];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);  // Criptografa a senha

$status = 'true';
// Monta a consulta SQL para inserção
$query = "INSERT INTO usuarios (
nome, cpf, dataNascimento, telefone, email, senha, status
) VALUES (
:nome, :cpf, :dataNascimento, :telefone, :email, :senha, :status
)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':dataNascimento', $dataNascimento);
$stmt->bindParam(':telefone', $telefone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);
$stmt->bindParam(':status', $status);

// Executa a inserção
if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/novo-colaborador.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
