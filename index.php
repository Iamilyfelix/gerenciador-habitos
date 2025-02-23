<?php
session_start();
//echo $_SESSION['email'];//aqui ele ta testando se esta pegando o session

if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";

}else{
    try {
        // Conexão com o PostgreSQL usando PDO
        $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Obtém o e-mail da sessão
        $email_usuario = $_SESSION['email'];


        // Obtém o e-mail da sessão
        // $email_usuario = $_SESSION['email'];
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
        //echo "Seu ID é: " . $usuario_id;
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
    
                echo "<p class='acerto'>Hábito cadastrado com sucesso!</p>";
            } catch (PDOException $e) {
                echo "<p class='erro'>Erro ao cadastrar: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p class='erro'>Por favor, insira um nome para o hábito.</p>";
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
            echo "<p class='erro'>Erro ao buscar hábitos: " . $e->getMessage() . "</p>";
        }

    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/login_cadastro_index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <title>Gerenciador de Hábitos</title>
</head>
<body>
    <div class="header-habitos">
        <h2>Registro de Hábitos</h2>
    </div>
    <div class="container-index">
        <div class="form-cadastro-habitos col-sm-6">
            <form method="POST">
                <div class="input-group">
                    <input type="text" id="nome_habito" name="nome_habito" class="form-control" placeholder="Digite seu hábito" aria-label="Example text with button addon" aria-describedby="button-addon1">
                    <button class="btn btn-secondary" type="submit">Cadastrar</button>
                </div>
                <div id="passwordHelpBlock" class="form-text">
                    Exemplo: Fazer exercícios, beber 2L de água por dia.
                </div>
                
            </form>
        </div>
        <div class="form-salvar-habitos col-sm-6">
            <form action="salvar_habitos.php" method="POST" >
                <label for="data">Data do Registro:</label>
                <input class="form-control" type="date" id="data" name="data" required>
                <div class="tudo">
                    <div class="title">
                        <h2>Habitos</h2>
                        <div id="passwordHelpBlock" class="form-text">
                            Os seus habitos cadastrados aparecerão aqui.
                        </div>
                    </div>

                    <?php foreach ($habitos as $habito): ?>
                    <div class="box-habitos-salvos">
                        <label>
                            <input class="form-check-input" type="checkbox" name="controle_habitos[]" value="<?= $habito['id']; ?>">
                            <a href="deletar_habito.php?id=<?= $habito['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este hábito?');"><i class="bi bi-trash3-fill"></i></a>
                            <?= htmlspecialchars($habito['nome']); ?>
                        </label>
                    <?php endforeach; ?>       
                    </div>

                    <div class="buton-habitos">
                        <button class="btn btn-secondary" type="submit" name="submit" >Salvar</button>
                        <a href="historico.php">Historico</a>
                        <a href="tarefas.php">Tarefas</a>
                    </div>
                </div>
                </form>
            </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script> 
</body>
</html>
<?php 
}


?>

