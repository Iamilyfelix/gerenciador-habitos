<?php
session_start();
$email_usuario = $_SESSION['email'];

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (!isset($_GET['id'])) {
    echo "<script>alert('Faça login!'); window.location.href='tarefas.php';</script>";
    exit;
}

// Busca o ID do usuário pelo e-mail
$sql = "SELECT id FROM usuario WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email_usuario);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}

$usuario_id = $usuario['id'];

// Verifica se a variável de sessão para usuario_id está definida
if (!isset($_SESSION['usuario_id'])) {
    die("Usuário não autenticado.");
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
    die();  // Interrompe o script
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/historico_tarefa.css">
</head>
<body>
    <div class="header">
        <h2>Editar Tarefa</h2>
    </div>

    <form class="form" action="atualizar_tarefas.php" method="POST">
        <div class="input col-md-6">
            <div class="mb-3">
                <input type="hidden" name="id" value="<?= $tarefa['id']; ?>">
                <label  class="form-label" for="titulo">Título:</label>
                <input class="form-control" type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" id="descricao" name="descricao" required><?= htmlspecialchars($tarefa['descricao']); ?></textarea>
            </div>

            <button class="btn btn-secondary" type="submit">Salvar Alterações</button>
        </div>
    </form>
    <div class="link">
        <a href="index.php">Sair</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
</body>
</html>

