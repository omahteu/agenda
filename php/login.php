<?php
session_start(); // Iniciar a sessão

require_once 'Database.php';
require_once 'Crud.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['usuario'];
    $password = $_POST['senha'];

    // Obter a conexão com o banco de dados
    $database = new Database();
    $db = $database->getConnection();

    // Instanciar o CRUD
    $crud = new Crud($db);

    // Query para verificar o usuário
    $query = "SELECT * FROM usuarios WHERE email = :email";
    
    // Preparar e executar a query
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['senha'])) {
        // Senha correta, criar a sessão do usuário
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        // Obtém o URI completo (path + query string)

        // Redirecionar para o dashboard
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        header("Location: ../pages/samples/dash.php");


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
