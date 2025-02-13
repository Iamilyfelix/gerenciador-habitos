<?php
session_start(); // Esta linha precisa ser a primeira
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="box-form">
        <form action="processa_login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <br><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <br><br>

            <button type="submit" name="submit">Login</button>
            <a href="cadastro.php">Cadastre-se</a>
        </form>
    </div>

</body>
</html>