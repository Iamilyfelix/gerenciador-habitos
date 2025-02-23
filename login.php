<?php
session_start(); // Esta linha precisa ser a primeira
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
        <!-- <div class="link-login">
            <a href="cadastro.php">Cadastre-se</a>
        </div> -->

        <form class="form-login_cadastro" action="processa_login.php" method="POST">
            <p>Faça seu login</p>
            <div class="mb-2">
                <input type="email" class="form-control" id="email" name="email" required placeholder="Digite seu Email">
                <div id="passwordHelpBlock" class="form-text">
                    exemplo@dominio.com
                </div>
            </div>
            
            <div class="mb-3">
                <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
            </div>

            <button type="submit" name="submit" class="btn btn-secondary">Login</button>
        </form>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0JqzY6YAGTm0xQq3aB32ZCqVo8GBr84FbO/tp9O1cHq9p6/9" crossorigin="anonymous"></script>
</body>
</html>