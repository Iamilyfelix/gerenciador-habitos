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

$sql = "SELECT * FROM tarefas WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarefa) {
    echo "Tarefa não encontrada!";
    exit;
}
?>

<h2>Editar Tarefa</h2>
<form action="atualizar_tarefas.php" method="POST">
    <input type="hidden" name="id" value="<?= $tarefa['id']; ?>">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']); ?>" required>
    <br>
    <label for="descricao">Descrição:</label>
    <textarea id="descricao" name="descricao" required><?= htmlspecialchars($tarefa['descricao']); ?></textarea>
    <br>
    <button type="submit">Salvar Alterações</button>
</form>
<a href="tarefas.php">Voltar</a>
