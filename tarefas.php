<?php
  session_start();
  $email_usuario = $_SESSION['email'];
if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";// como faço pra tirar o aparecimento do erro

}else{
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email_usuario = $_SESSION['email'];
    // Busca o ID do usuário pelo e-mail
    $sql = "SELECT id FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email_usuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuário não encontrado.");
    }

    $usuario_id = $usuario['id']; // ID do usuário logado

    // Buscar todas as tarefas do usuário
    $sql = "SELECT * FROM tarefas WHERE usuario_id = :usuario_id ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
</html>
<div class="header">
    <h2>Minhas Tarefas</h2>
</div>

<form class="form" action="salvar_tarefas.php" method="POST">
    <!-- Formulário para adicionar nova tarefa -->
    <div class="input col-sm-6 ">
        <div class="mb-3">
            <label for="titulo" class="form-label">Titulo:</label>
            <input type="text" id="titulo" name="titulo" required class="form-control" placeholder="O que precisa ser feito?">
        </div>
        <div class="mb-3">
            <label for="descrição" class="form-label">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao" required placeholder="Detalhes da tarefa..." rows="2"></textarea>
        </div>
        <button class="btn btn-secondary" type="submit">Adicionar Tarefa</button>
    </div>
</form>


<!-- Lista de Tarefas -->
    <table border="1" class="table table-secondary table-tarefas col-lg-6">
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($tarefas as $tarefa): ?>
            <tr>
                <td><?= htmlspecialchars($tarefa['titulo']); ?></td>
                <td><?= htmlspecialchars($tarefa['descricao']); ?></td>
                <td>
                    <a href="editar_tarefas.php?id=<?= $tarefa['id'];?>">Editar</a>
                    <a href="remover_tarefas.php?id=<?= $tarefa['id'];?>" onclick="return confirm('Tem certeza que você concluiu essa tarefa?');">Concluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div class="link">
        <a href="index.php">Sair</a>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
</body>
</html>
<?php
}

?>