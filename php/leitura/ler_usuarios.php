<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Ajustar a query para o formato brasileiro da data, tratar valores null e formatar status/is_staff
$query = "
    SELECT 
        IFNULL(nome, '') as nome, 
        IFNULL(cpf, '') as cpf, 
        IFNULL(DATE_FORMAT(dataNascimento, '%d/%m/%Y'), '') as dataNascimento, 
        IFNULL(telefone, '') as telefone, 
        IFNULL(email, '') as email, 
        IFNULL(CASE 
            WHEN status = 1 THEN 'Ativo' 
            ELSE 'Inativo' 
        END, '') as status,
        IFNULL(CASE 
            WHEN is_staff = 1 THEN 'Administrativo' 
            ELSE 'Colaborativo' 
        END, '') as is_staff
    FROM usuarios";

$result = $crud->read($query);

header('Content-Type: application/json');
echo json_encode($result);
?>
