<?php
  session_start();
  $email_usuario = $_SESSION['email'];
if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";// como faço pra tirar o aparecimento do erro

}
else{
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
    <html>
    <h2>Minhas Tarefas</h2>

    <!-- Formulário para adicionar nova tarefa -->
    <form action="salvar_tarefas.php" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
        <br>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required></textarea>
        <br>
        <button type="submit">Adicionar Tarefa</button>
    </form>

    <hr>

    <!-- Lista de Tarefas -->
    <table border="1">
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
                    <a href="editar_tarefas.php?id=<?= $tarefa['id']; ?>">✏️ Editar</a>
                    <a href="remover_tarefas.php?id=<?= $tarefa['id']; ?>" onclick="return confirm('Tem certeza que você concluiu essa tarefa?');">✅ Concluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    </html>
    <?php
}
