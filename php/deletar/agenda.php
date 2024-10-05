<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Prepara a query de exclusão
    $query = "DELETE FROM diario WHERE id = :id";
    // Prepara o array de parâmetros
    $params = [':id' => $id];

    // Executa a exclusão, mas sem modificar o método delete
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    if ($stmt->rowCount() > 0) 
    {
        echo json_encode(
            [
                "message" => "Registro excluído com sucesso."
            ]
        );
    } else {
        echo json_encode(
            [
                "message" => "Erro ao excluir o registro."
            ]
        );
    }
} else {
    echo json_encode(["message" => "Método de requisição inválido."]);
}
?>
