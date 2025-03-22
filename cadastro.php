<?php
    session_start();

    if(isset($_POST['submit']))
    {
        try{
            // Conexão com o banco de dados PostgreSQL
            $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');

            // Recebendo os dados do formulário
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            // Inserindo os dados no banco, sem o ID
            $sql = "INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->execute();

            $_SESSION['email'] = $email ;
            // Redireciona para a tela "index.php"
            header("Location: index.php");
            exit(); // Finaliza o script para garantir o redirecionamento
        }catch (PDOException $e) {
            if($e->getCode() == '23505') {
                echo "<h2 style='color: red;'>Esse email já existe!</h2>";
            } else {
                echo "<h2 style='color: red;'>Erro no cadastro: " . $e->getMessage() . "</h2>";
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./styles/login_cadastro_index.css">
</head>
<body>
    <div class="box-login_cadastro">
        <a href="login.php">Login</a>

        <form class="form-login_cadastro" action="cadastro.php" method="POST">
            <p>Faça seu cadastro</p>

            <div class="mb-2">
                <input type="text" class="form-control" id="nome" name="nome" required placeholder="Digite seu Nome" >
            </div>        

            <div class="mb-2">
                <input type="email" class="form-control" id="email" name="email" required placeholder="exemplo@dominio.com">
            </div>
                
            <div class="mb-2">
                <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>

            <button class="btn btn-secondary buton-login" type="submit" name="submit">Cadastrar</button>
                
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script>
</body>
</html>
