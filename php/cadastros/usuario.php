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
$senha = $_POST['senha']; // Removendo o hash temporariamente para validar o tamanho
$is_staff = isset($_POST['perfil']) ? intval($_POST['perfil']) : null;

$status = 1;

// Verifica se o perfil foi selecionado
if (is_null($is_staff)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'É necessário escolher um perfil para o colaborador.']);
    exit();
}


// 1. Verifica se o CPF tem apenas números
if (!ctype_digit($cpf)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'CPF inválido. O CPF deve conter apenas números.']);
    exit();
}

// 2. Verifica se o CPF tem exatamente 11 caracteres
if (strlen($cpf) != 11) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'CPF inválido. O CPF deve conter exatamente 11 caracteres.']);
    exit();
}

// 3. Verifica se o email é válido
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'Email inválido. Por favor, insira um email válido.']);
    exit();
}

// 4. Verifica se a senha tem mais de 4 caracteres
if (strlen($senha) <= 4) {
    http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
    echo json_encode(['error' => 'Senha inválida. A senha deve conter mais de 4 caracteres.']);
    exit();
}

// Agora que a senha foi validada, faça o hash
$senha = password_hash($senha, PASSWORD_DEFAULT);

// Monta a consulta SQL para inserção
$query = "INSERT INTO usuarios (
nome, cpf, dataNascimento, telefone, email, senha, status, is_staff
) VALUES (
:nome, :cpf, :dataNascimento, :telefone, :email, :senha, :status, :is_staff
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
$stmt->bindParam(':is_staff', $is_staff);

// Executa a inserção
if ($stmt->execute()) {
    http_response_code(201); // Define o código de status HTTP para 201 (Created)
    echo json_encode(['message' => 'Usuário criado com sucesso!']);
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    http_response_code(500); // Define o código de status HTTP para 500 (Erro interno no servidor)
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
