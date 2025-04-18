<?php
    session_start(); // Inicia a sessão 

    //se as variaveis não estiver vazia entre 
    if(isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha']))
    {   
        try{
            // Conexão com o banco de dados PostgreSQL
            $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');

            // pega o valor armazenado no input e guarda na variavel
            $email=$_POST['email'];
            $senha=$_POST['senha'];

           

            
            // Prepare a consulta para pegar o email e senha
            $sql="SELECT * FROM usuario WHERE email=:email and senha=:senha";
            $stmt = $pdo->prepare($sql);

            //associa os valores guardados na variavel com os parametros
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            // Fazer a pergunta ao banco de dados 
            $stmt->execute();

            // Pega uma linha de resultado (se tiver)
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($email == "adm@gmail.com" && $senha == "1234") {
                $_SESSION['email'] = $email;
                $_SESSION['senha'] = $senha;
                header("Location: crud_adm.php");
                exit();
            }
            // Se o resultado não for vazio, significa que encontramos
            if ($resultado) {
                // O cliente foi encontrado

                $_SESSION['email'] = $email ;
                header("Location: index.php");
                exit();
            }else{
                //Email ou senha inválidos
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