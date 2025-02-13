<?php
	// Verificar se foi enviando dados via POST
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
		$nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : "";
		$email = (isset($_POST["email"]) && $_POST["email"] != null) ? $_POST["email"] : "";
		$celular = (isset($_POST["celular"]) && $_POST["celular"] != null) ? $_POST["celular"] : NULL;
	}
	else if (!isset($id)) {
		// Se não se não foi setado nenhum valor para variável $id
		$id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
		$nome = NULL;
		$email = NULL;
		$celular = NULL;
	}
	try {
		$conexao = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=crudsimples', 'postgres','pabd');
		$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// Configura o banco para usar UTF-8
		$conexao->exec("SET client_encoding TO 'UTF8'");
		echo "uhull"; 
	} catch (PDOException $erro) {
		echo "Erro na conexão: " . $erro->getMessage();
	}
	if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
		try {
			$stmt = $conexao->prepare("INSERT INTO contatos (nome, email, celular) VALUES (?, ?, ?)");
			$stmt->bindParam(1, $nome);
			$stmt->bindParam(2, $email);
			$stmt->bindParam(3, $celular);
			 
			if ($stmt->execute()) {
				if ($stmt->rowCount() > 0) {
					echo "Dados cadastrados com sucesso!";
					$id = null;
					$nome = null;
					$email = null;
					$celular = null;
				} else {
					echo "Erro ao tentar efetivar cadastro";
				}
			} else {
				   throw new PDOException("Erro: Não foi possível executar a declaração sql");
			}
		} catch (PDOException $erro) {
			echo "Erro: " . $erro->getMessage();
		}
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Agenda de contatos</title>
    </head>
    <body>
        <form action="?act=save" method="POST" name="form1"  >
          <h1>Agenda de contatos</h1>
          <hr>
          <input type="hidden" name="id" <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?> />
          Nome:
          <input type="text" name="nome" <?php
            // Preenche o nome no campo nome com um valor "value"
            if (isset($nome) && $nome != null || $nome != ""){
                echo "value=\"{$nome}\"";
            }
            ?> />
          E-mail:
          <input type="text" name="email" <?php
            // Preenche o email no campo email com um valor "value"
            if (isset($email) && $email != null || $email != ""){
                echo "value=\"{$email}\"";
            }
            ?> />
          Celular:
         <input type="text" name="celular" <?php
            // Preenche o celular no campo celular com um valor "value"
            if (isset($celular) && $celular != null || $celular != ""){
                echo "value=\"{$celular}\"";
            }
            ?> />
         <input type="submit" value="salvar" />
         <input type="reset" value="Novo" />
         <hr>
       </form>
	   <table border="1" width="100%">
			<tr>
				<th>Nome</th>
				<th>E-mail</th>
				<th>Celular</th>
				<th>Ações</th>
			</tr>
			<?php
			try {
 
				$stmt = $conexao->prepare("SELECT * FROM contatos");
			 
					if ($stmt->execute()) {
						while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
							echo "<tr>";
							echo "<td>".$rs->nome."</td><td>".$rs->email."</td><td>".$rs->celular
									   ."</td><td><center><a href=\"?act=upd&id=" . $rs->id .  "\">[Alterar]</a>"
									   ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
									   ."<a href=\"?act=del&id=" . $rs->id . "\">[Excluir]</a></center></td>";
							echo "</tr>";
						}
					} else {
						echo "Erro: Não foi possível recuperar os dados do banco de dados";
					}
			} catch (PDOException $erro) {
				echo "Erro: ".$erro->getMessage();
			}
			?>
		</table>

    </body>
</html>


