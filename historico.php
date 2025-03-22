<?php
session_start();
$email_usuario = $_SESSION['email'];

if (!isset($_SESSION['email'])) {
    $_SESSION['mensagem'] = "Voce não pode acessar essa pagina sem ter feito login";
    echo "<script>
        alert('" . $_SESSION['mensagem'] . "');
        window.location.href = 'login.php';
    </script>";

    unset($_SESSION['mensagem']); // Limpa a sessão
    exit();

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

    $sql = "SELECT h.nome, rh.data, rh.concluido 
            FROM registro_habitos rh
            JOIN habitos h ON rh.habito_id = h.id
            WHERE rh.usuario_id = :usuario_id
            ORDER BY rh.data DESC";


    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="./styles/historico_tarefa.css">
        <title>Document</title>
    </head>
    <body>
        
    <div class="header">
        <h2>Histórico</h2>
    </div>
    
    <div class="table-historico">
        <table border="1" class="table table-secondary">
            <tr>
                <th>Hábito</th>
                <th>Data</th>
                <th>Status</th>
            </tr>
            <?php foreach ($historico as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['nome']); ?></td>
                    <td><?= date("d/m/Y", strtotime($registro['data'])); ?></td>
                    <td><?= $registro['concluido'] ? '✅ Concluído' : '❌ Não concluído'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="link">
        <a href="index.php">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
    </body>
    </html>
    <?php
}
?>

