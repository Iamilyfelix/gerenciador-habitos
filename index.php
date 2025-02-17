<?php
session_start(); 
echo $_SESSION['email'];//aqui ele ta testando se esta pegando o session


/////////////.

if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";

}else{
    try {
        // Conexão com o PostgreSQL usando PDO
        $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Obtém o e-mail da sessão
        $email_usuario = $_SESSION['email'];
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
    
    // Obtém o e-mail da sessão
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

    // Exibir o ID do usuário (apenas para testes)
    echo "Seu ID é: " . $usuario_id;
    /////////////.
    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nome_habito'])) {
        $nome_habito = trim($_POST['nome_habito']);
    
        if (!empty($nome_habito)) {
            try {
                $sql = "INSERT INTO habitos (nome,usuario_id) VALUES (:nome, :usuario_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome_habito);
                $stmt->bindParam(':usuario_id', $usuario_id);
                $stmt->execute();
    
                echo "<p style='color: green;'>Hábito cadastrado com sucesso!</p>";
            } catch (PDOException $e) {
                echo "<p style='color: red;'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Por favor, insira um nome para o hábito.</p>";
        }
    }
    
    // Buscar hábitos já cadastrados
    $habitos = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM habitos WHERE usuario_id = :usuario_id ORDER BY id DESC");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute(['usuario_id' => $usuario_id]);
        $habitos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erro ao buscar hábitos: " . $e->getMessage() . "</p>";
    }

    ?>
    <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Hábitos</title>
</head>
<body>
    <h2>Cadastrar Hábito</h2>
    <form method="POST">
        <label for="nome_habito">Nome do Hábito:</label>
        <input type="text" id="nome_habito" name="nome_habito" >
        <button type="submit">Cadastrar</button>
    </form>

    <h2>Hábitos Cadastrados</h2>
    <form action="salvar_habitos.php" method="POST" >
            <label for="data">Data do Controle:</label>
            <input type="date" id="data" name="data" required>
            <br>
        <?php foreach ($habitos as $habito): ?>
            <label>
                <input type="checkbox" name="controle_habitos[]" value="<?= $habito['id']; ?>">
                <?= htmlspecialchars($habito['nome']); ?>
            </label>
            <a href="deletar_habito.php?id=<?= $habito['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este hábito?');">❌</a>
            <br>
        <?php endforeach; ?>
        <br>
        <button type="submit" name="submit" >Salvar</button>
        <a href="historico.php">meu historico</a>
        <a href="metas.php">minhas metas</a>
    </form>
</body>
</html>
<?php 
}


?>

