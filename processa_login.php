<?php
     session_start(); // Inicia a sessão
     // Supondo que você tenha validado o login do usuário no banco:
   
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {   
        try{
            // Conexão com o banco de dados PostgreSQL
            $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');

            //ACESSA
            $email=$_POST['email'];
            $senha=$_POST['senha'];

            $_SESSION['email'] = $usuario_email;

            
            // Prepare a consulta para pegar o email e senha
            $sql="SELECT * FROM usuario WHERE email=:email and senha=:senha";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            // Fazer a pergunta ao banco de dados
            $stmt->execute();
            // Pega uma linha de resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Se o resultado não for vazio, significa que encontramos

            if ($resultado) {
                // O cliente foi encontrado

                /*echo "Login realizado com sucesso!";
                echo "Bem-vindo, " . $resultado['nome'];
                */
                $_SESSION['email'] = $email ;
                header("Location: index.php");
                exit();
            } else {
            // Email ou senha inválidos
                echo "Credenciais inválidas.";
            }
        } catch (PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
        }
    }
    else{
        //NAO ACESSA
        header('location:login.php');
        exit();
    }
?>