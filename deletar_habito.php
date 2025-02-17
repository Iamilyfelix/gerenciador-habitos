<?php
 // Conecte-se ao banco de dados

    // Conexão com o PostgreSQL usando PDO
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar a exclusão no banco
    $sql = "DELETE FROM habitos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Hábito excluído com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir hábito!'); window.location.href='index.php';</script>";
    }
} else {
    echo "<script>alert('ID inválido!'); window.location.href='index.php';</script>";
}
?>
