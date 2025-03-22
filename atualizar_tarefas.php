<?php
$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recupera os dados do formulário e da sessão
    $id = $_POST['id'];
    $usuario_id = $_SESSION['usuario_id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];

    //consulta SQL para atualizar uma tarefa no banco de dados
    $sql = "UPDATE tarefas SET titulo = :titulo, descricao = :descricao WHERE id = :id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: tarefas.php");
        exit;
    } else {
        echo "<script>alert('Erro ao atualizar tarefa!');</script>";
    }
}
?>
