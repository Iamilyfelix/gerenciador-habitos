<?php
session_start(); 
echo $_SESSION['email'];

if (!isset($_SESSION['email'])) {
    echo " voce n pode acessar essa pagina sem ter feito login";

}
?>

<form method="POST">
    <label>Selecione a data:</label>
    <input type="date" name="data" required>
    <br><br>

    <?php foreach ($habitos as $habito): ?>
        <label>
            <input type="checkbox" name="controle_habitos[]" value="<?= $habito['id']; ?>">
            <?= htmlspecialchars($habito['nome']); ?>
        </label>
        <br>
    <?php endforeach; ?>

    <br>
    <button type="submit">Salvar</button>
</form>