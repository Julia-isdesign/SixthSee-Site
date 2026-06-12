<?php
# Includes essenciais
require_once("../_controle/config.php");
require_once("../_controle/modulos/emails.php");


# Para testar e visualizar erros
# $teste = true;


# Se o checkbox de consentimento estiver desmarcado, retorna erro
if ((int)$_POST["consentimento"] != 1) {
	$resposta = [
		"status" => 0,
		"mensagem" => "Para enviar o e-mail, й preciso consentir com o armazenamento dos dados"
	];
}
else {
	# Codificar o envio
	utf8_decode_array($_POST);

	# Identificaзгo da pбgina que faz o envio
	$idPai = (int)$_POST["idPai"];

	# Identificaзгo do destino cadastrado no painel
	$consulta = consultar("SELECT titulo, destino FROM paginas WHERE id = $idPai");
	$destino = $consulta["dados"][0]["destino"];
	$nomePagina = $consulta["dados"][0]["titulo"];

	# Retorna erro se nгo houver destino
	if (!$destino) {
		$resposta["status"] = 0;
	}
	else {
		# Identificaзгo do estado e da cidade, se estiverem presentes no formulбrio
		if (isset($_POST["estado"])) {
			$consulta = consultar("SELECT uf FROM estados WHERE id = ".(int)$_POST["estado"]);
			$_POST["estado"] = $consulta["dados"][0]["uf"];
		}
		if (isset($_POST["cidade"])) {
			$consulta = consultar("SELECT nome FROM cidades WHERE id = ".(int)$_POST["cidade"]);
			$_POST["cidade"] = $consulta["dados"][0]["nome"];
		}

		# Envio do formulбrio com o Mуdulo e-mails
		$resposta["status"] = ModuloEmails::enviarEmailPadrao(1, ["destino" => $destino, "nomePagina" => $nomePagina] + $_POST, 0);
	}
}

# Retorno da resposta em formato JSON
echo arrayToJSON($resposta);
?>