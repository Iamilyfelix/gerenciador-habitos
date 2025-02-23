<?php
session_start();

// Defina o email e a senha do administrador manualmente
$admin_email = "adm@gmail.com";
$admin_senha = "1234"; // Senha em texto puro (não recomendado para produção)

// Verifica se a sessão está ativa e se os dados conferem
if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || 
    $_SESSION['email'] !==  "adm@gmail.com" || $_SESSION['senha'] !== "1234") {
    echo "Você não tem permissão para acessar esta página!";
    exit();
}

$pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');


// Adicionar Usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $senha]);
    header("Location: crud_adm.php");
    exit();
}

// Excluir Usuário
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: crud_adm.php");
    exit();
}

// Listar Usuários
$stmt = $pdo->query("SELECT * FROM usuario");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="header">
        <h2>Gerenciar Usuários</h2>
    </div>

    <form class="form"method="POST">
        <div class="input col-sm-6">
            <label>Nome:</label>
            <input class="form-control" placeholder="Digite o nome do usuario" type="text" name="nome" required><br>

            <label>Email:</label>
            <input class="form-control" placeholder="exemplo@dominio.com" type="email" name="email" required><br>

            <label>Senha:</label>
            <input class="form-control" placeholder="Digite uma senha" type="password" name="senha" required><br>

            <div class="buton-crud">
                <button class="btn btn-secondary" type="submit" name="adicionar">Cadastrar</button>
                <a href="login.php">Logout</a>
            </div>
            
        </div>
    </form>

    <h3>Lista de Usuários</h3>
    <table border="1" class="table table-secondary">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Senha</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nome'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td><?= $usuario['senha'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">Editar</a>
                    <a href="crud_adm.php?excluir=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
</body>
</html>
