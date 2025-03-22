<?php
session_start(); 

//aqui ele ta testando se esta pegando o session
//echo $_SESSION['email'];

if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";

}else{

    try {
        // Conexão com o PostgreSQL usando PDO
        $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
    // Obtém o e-mail da sessão
    $email_usuario = $_SESSION['email'];
    
    //pega o id do usuario onde o email é o mesmo guardado no session
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

    //Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $email = $_SESSION['email'];
        $data = $_POST['data'];  // Data escolhida pelo usuário - estou pegando la do input da pag index
    
        if ($usuario) {
            $id_usuario = $usuario['id'];

            //TESTE
            //echo"seu id é".$id_usuario;
    
            // Recuperar todos os hábitos do usuário
            $stmt = $pdo->prepare("SELECT id FROM habitos WHERE usuario_id = :usuario_id");
            $stmt->execute(['usuario_id' => $id_usuario]);
            $habitos = $stmt->fetchAll(PDO::FETCH_COLUMN);

            //TESTE
            //var_dump($habitos);
            
    
            if ($habitos) {
                foreach ($habitos as $id_habito) { //percorre os hábitos existentes e verifica se cada um deles foi marcado no formulário.
                    // verifica se o ID do hábito atual esta dentro do array se sim retorna true se não false
                    $concluido = isset($_POST['registro_habitos']) && in_array($id_habito, $_POST['registro_habitos']) ? 1 : 0;
    
                    //  ele insere essa informação no banco de dados
                    $stmt = $pdo->prepare("INSERT INTO registro_habitos (usuario_id, habito_id, data, concluido) 
                                           VALUES (:usuario_id, :habito_id, :data, :concluido)");
                    $stmt->execute([
                        'usuario_id' => $id_usuario,
                        'habito_id' => $id_habito,
                        'data' => $data,
                        'concluido' => $concluido
                    ]);
                }
    
                
                $_SESSION['mensagem'] = "Hábito(s) salvo(s) com sucesso!";
                echo "<script>
                    alert('" . $_SESSION['mensagem'] . "');
                    window.location.href = 'index.php';
                </script>";

                unset($_SESSION['mensagem']); // Limpa a sessão
                exit();
            } else {
                echo "<p style='color: red;'>Nenhum hábito encontrado para o usuário.</p>";
            }
        } else {
            echo "<p style='color: red;'>Usuário não encontrado!</p>";
        }
    
    }
    
}
?>