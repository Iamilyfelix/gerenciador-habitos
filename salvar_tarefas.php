<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

$email_usuario = $_SESSION['email'];
// Busca o ID do usuário pelo e-mail
$sql = "SELECT id FROM usuario WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<script>alert('Faça login!'); window.location.href='tarefas.php';</script>";
}

$_SESSION['usuario_id'] = $usuario['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    $sql = "INSERT INTO tarefas (usuario_id, titulo, descricao) VALUES (:usuario_id, :titulo, :descricao)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: tarefas.php");
        exit;
    } else {
        echo "<script>alert('Erro ao adicionar tarefa!');</script>";
    }
}
?>
