<?php
session_start(); 
echo $_SESSION['email'];//aqui ele ta testando se esta pegando o session

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


    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = $_SESSION['email'];
        $data = $_POST['data'];  // Data escolhida pelo usuário
    
        //isso ta prestando
    
        if ($usuario) {
            $id_usuario = $usuario['id'];
            echo"seu id é".$id_usuario;
    
            // Recuperar todos os hábitos do usuário
            $stmt = $pdo->prepare("SELECT id FROM habitos WHERE usuario_id = :usuario_id");
            $stmt->execute(['usuario_id' => $id_usuario]);
            $habitos = $stmt->fetchAll(PDO::FETCH_COLUMN);
            var_dump($habitos);//teste
    
            if ($habitos) {
                foreach ($habitos as $id_habito) {
                    // Verifica se o hábito foi marcado pelo usuário no formulário
                    $concluido = isset($_POST['controle_habitos']) && in_array($id_habito, $_POST['controle_habitos']) ? 1 : 0;
    
                    // Insere um novo controle com a data e o status correto
                    $stmt = $pdo->prepare("INSERT INTO controle_habitos (usuario_id, habito_id, data, concluido) 
                                           VALUES (:usuario_id, :habito_id, :data, :concluido)");
                    $stmt->execute([
                        'usuario_id' => $id_usuario,
                        'habito_id' => $id_habito,
                        'data' => $data,
                        'concluido' => $concluido
                    ]);
                }
    
                //NÃO ESTOU GOSTANDO DISSO - PERGUNTAR A DANIEL  
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
<!--dar o delete nos habitos ja salvos 
criar historico 
-->