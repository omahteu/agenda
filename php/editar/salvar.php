<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$id = intval($_POST['id']); // ID do usuário que será atualizado
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$dataNascimento = $_POST["nascimento"];
$telefone = $_POST["telefone"];
$email = $_POST['email'];
// $senha = $_POST['senha']; // Removendo o hash temporariamente para validar o tamanho
$is_staff = intval($_POST['perfil']);
$status = 1; // Exemplo: o status está sendo fixado como 1

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

// 4. Verifica se a senha tem mais de 6 caracteres (somente atualiza se for fornecida uma senha)
// if ($senha && strlen($senha) <= 6) {
//     http_response_code(400); // Define o código de status HTTP para 400 (Bad Request)
//     echo json_encode(['error' => 'Senha inválida. A senha deve conter mais de 6 caracteres.']);
//     exit();
// }

// Monta a consulta SQL para atualização (inclui a senha apenas se fornecida)
$query = "UPDATE usuarios SET 
    nome = :nome, 
    cpf = :cpf, 
    dataNascimento = :dataNascimento, 
    telefone = :telefone, 
    email = :email, 
    status = :status, 
    is_staff = :is_staff";

// Adiciona o campo senha à consulta somente se ela foi fornecida
// if (!empty($senha)) {
//     // Faz o hash da senha
//     $senha = password_hash($senha, PASSWORD_DEFAULT);
//     $query .= ", senha = :senha";
// }

$query .= " WHERE id = :id";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':cpf', $cpf);
$stmt->bindParam(':dataNascimento', $dataNascimento);
$stmt->bindParam(':telefone', $telefone);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':is_staff', $is_staff);
$stmt->bindParam(':id', $id);

// Liga o parâmetro da senha se ela for fornecida
// if (!empty($senha)) {
//     $stmt->bindParam(':senha', $senha);
// }

// Executa a atualização
if ($stmt->execute()) {
    http_response_code(200); // Define o código de status HTTP para 200 (OK)
    echo json_encode(['message' => 'Usuário atualizado com sucesso!']);
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    http_response_code(500); // Define o código de status HTTP para 500 (Erro interno no servidor)
    echo json_encode(['error' => $errorInfo[2]]);
}

?>
