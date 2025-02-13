<?php
    try {
		$conexao = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=gerenciador', 'postgres','pabd');
		$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// Configura o banco para usar UTF-8
		$conexao->exec("SET client_encoding TO 'UTF8'");
	} catch (PDOException $erro) {
		echo "Erro na conexão: " . $erro->getMessage();
	}
?>