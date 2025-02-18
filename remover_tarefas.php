<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

if (!isset($_GET['id'])) {
    echo "<script>alert('Faça login!'); window.location.href='tarefas.php';</script>";
    exit;
}

$id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

$sql = "DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: tarefas.php");
    exit;
} else {
    echo "<script>alert('Erro ao concluir tarefa!');</script>";
}
?>
