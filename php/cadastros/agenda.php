<?php

require_once '../Database.php';
require_once '../Crud.php';

$database = new Database();
$db = $database->getConnection();

$crud = new Crud($db);

// Coleta os dados do formulário
$data = $_POST['data'];
$colaborador = $_POST['colaborador'];
$hospital = $_POST['hospital'];
$material = $_POST['material'];
$medico = $_POST['medico'];
$convenio = $_POST['convenio'];
$horario_inicio = $_POST['horario_inicio'];
$horario_fim = $_POST['horario_fim'];
$observacoes = $_POST['observacoes'];
$status = 1;

// Verifica se já existe um compromisso no mesmo dia e horário
$query_check = "
    SELECT * FROM diario 
    WHERE colaborador = :colaborador 
      AND data = :data
      AND ((:horario_inicio BETWEEN horario_inicio AND horario_fim)
      OR (:horario_fim BETWEEN horario_inicio AND horario_fim)
      OR (horario_inicio BETWEEN :horario_inicio AND :horario_fim)
      OR (horario_fim BETWEEN :horario_inicio AND :horario_fim))
      AND hospital != :hospital"; // Verifica se o hospital é diferente

$stmt_check = $db->prepare($query_check);
$stmt_check->bindParam(':colaborador', $colaborador);
$stmt_check->bindParam(':data', $data);
$stmt_check->bindParam(':horario_inicio', $horario_inicio);
$stmt_check->bindParam(':horario_fim', $horario_fim);
$stmt_check->bindParam(':hospital', $hospital); // Parâmetro hospital
$stmt_check->execute();

// Se houver conflito em um hospital diferente, bloqueia o registro
if ($stmt_check->rowCount() > 0) {
    echo json_encode(
        [
            'error' => 'Não é possível registrar essa atividade nesse horário pois o colaborador já está ocupado em outro hospital.',
        ]
    );
    exit();
}

// Monta a consulta SQL para inserção
$query = "
    INSERT INTO diario 
    (colaborador, data, horario_inicio, horario_fim, observacoes, status, hospital, material, medico, convenio)
    VALUES (:colaborador, :data, :horario_inicio, :horario_fim, :observacoes, :status, :hospital, :material, :medico, :convenio)";

// Prepara a consulta
$stmt = $db->prepare($query);

// Liga os parâmetros
$stmt->bindParam(':colaborador', $colaborador);
$stmt->bindParam(':data', $data);
$stmt->bindParam(':horario_inicio', $horario_inicio);
$stmt->bindParam(':horario_fim', $horario_fim);
$stmt->bindParam(':observacoes', $observacoes);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':hospital', $hospital);
$stmt->bindParam(':material', $material);
$stmt->bindParam(':medico', $medico);
$stmt->bindParam(':convenio', $convenio);

if ($stmt->execute()) {
    // Redireciona para o formulário após o sucesso
    header("Location: ../../pages/criar-agenda.html");
    exit();
} else {
    // Exibe a mensagem de erro
    $errorInfo = $stmt->errorInfo();
    echo json_encode(
        [
            'error' => $errorInfo[2],
        ]
    );
}
?>
