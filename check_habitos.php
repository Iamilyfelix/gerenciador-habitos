<?php

// Conectar ao banco de dados
try {
    $conexao = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres', 'pabd');
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    die("Erro de conexão: " . $erro->getMessage());
}

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['controle_habitos']) && isset($_POST['data'])) {
    $data = $_POST['data']; // Data informada pelo usuário

    foreach ($_POST['habitos_concluidos'] as $habito_id) {
        try {
            // Inserir os hábitos concluídos na tabela habitos_concluidos
            $stmt = $conexao->prepare("INSERT INTO controle_habitos (habito_id, data_conclusao) VALUES (:habito_id, :data)");
            $stmt->bindParam(':habito_id', $habito_id, PDO::PARAM_INT);
            $stmt->bindParam(':data', $data, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $erro) {
            echo "Erro ao salvar hábito concluído: " . $erro->getMessage();
        }
    }

    echo "Hábitos concluídos foram salvos com sucesso!";
}

// Buscar hábitos cadastrados no banco

?>