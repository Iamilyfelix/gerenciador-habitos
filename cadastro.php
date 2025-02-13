<?php
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

            $_SESSION['id'] = $usuario_id;

            echo "Cadastro realizado com sucesso!";
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
    <style>
        body{
            background-color:blue;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Faça seu cadastro</h2>
    <form action="cadastro.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br><br>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br><br>

        <button type="submit" name="submit">Cadastrar</button>
        <a href="login.php">Login</a>
    </form>
</body>
</html>