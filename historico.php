<?php
session_start();
$email_usuario = $_SESSION['email'];

if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";

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

    $sql = "SELECT h.nome, ch.data, ch.concluido 
            FROM controle_habitos ch
            JOIN habitos h ON ch.habito_id = h.id
            WHERE ch.usuario_id = :usuario_id
            ORDER BY ch.data DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Histórico de Hábitos</h2>
    <table border="1">
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
<?php
}
