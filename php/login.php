<?php
session_start(); // Iniciar a sessão

require_once 'Database.php';
require_once 'Crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $password = $_POST['senha'];

    // Obter a conexão com o banco de dados
    $database = new Database();
    $db = $database->getConnection();

    // Instanciar o CRUD
    $crud = new Crud($db);

    // Query para verificar o usuário
    $query = "SELECT * FROM usuarios WHERE cpf = :cpf";
    
    // Preparar e executar a query
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['senha'])) {
        // Senha correta, criar a sessão do usuário
        $_SESSION['user_id'] = $user['id'];

        // Definir cookie com o ID do usuário
        setcookie('user_id', $user['id'], time() + 43200, '/'); // Cookie válido por 1 hora

        // Redirecionar para o dashboard
        header("Location: ../pages/dash.html");
        exit();
    } else {
        // Senha incorreta, exibir alerta
        echo "<script>alert('Login falhou. Por favor, verifique suas credenciais e tente novamente.'); window.location.href='../index.html';</script>";
    }
} else {
    // Redirecionar para a página de login se a requisição não for POST
    header("Location: login.html");
    exit();
}
?>
