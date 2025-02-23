<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || 
    $_SESSION['email'] !==  "adm@gmail.com" || $_SESSION['senha'] !== "1234") {
    echo "Você não tem permissão para acessar esta página!";
    exit();
}

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');

// Buscar usuário
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Atualizar usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("UPDATE usuario SET nome = ?, email = ?, senha = ? WHERE id = ?");
    $stmt->execute([$nome, $email, $senha, $id]);

    header("Location: crud_adm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/historico_tarefa.css">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
    <div class="header">
        <h2>Editar Usuário</h2>
    </div>
    <form class="form" method="POST">
        <div class="input col-md-6">
            <input class="form-control" type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <label>Nome:</label>
            <input class="form-control" type="text" name="nome" value="<?= $usuario['nome'] ?>" required><br>

            <label>Email:</label>
            <input class="form-control" type="email" name="email" value="<?= $usuario['email'] ?>" required><br>

            <label>Senha:</label>
            <input class="form-control" type="password" name="senha" value="<?= $usuario['senha'] ?>" required><br>

            <button class="btn btn-secondary" type="submit">Atualizar</button>
            <a href="crud_adm.php">Voltar</a>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
</body>
</html>
