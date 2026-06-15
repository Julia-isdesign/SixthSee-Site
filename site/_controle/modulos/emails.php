<?php
require_once(DIRETORIO_SITE."_controle/config.php");
require_once(DIRETORIO_SITE."_controle/handlers/numeros.php");
require_once(DIRETORIO_SITE."_controle/handlers/textos.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(DIRETORIO_SITE."/_controle/plugins/phpmailer/src/Exception.php");
require_once(DIRETORIO_SITE."/_controle/plugins/phpmailer/src/PHPMailer.php");
require_once(DIRETORIO_SITE."/_controle/plugins/phpmailer/src/SMTP.php");

class ModuloEmails {

	const REMETENTE = "contato@6thsee.com.br";

	# CSSs
	const CSS = [
		"cor-azul" => "#184668",

		"fundo-1" => "#5e5e5e",
		"fundo-2" => "#ffffff",
		"fundo-3" => "#444",

		"margin" => "margin:0;",
		"margin-p" => "margin:10px 0 0;",
		"margin-m" => "margin:20px 0 0;",
		"margin-g" => "margin:40px 0 0;",
	];

	const ELEM = [
		"divisor" => "width:100%; height:2px; margin:auto; background:rgba(0,0,0,0.1);",
		"envelope" => "width:100%; min-width:320px; max-width:720px; padding:40px; margin:0 auto; box-sizing:border-box;",
	];

	const TEXTO = [
		"corpo" => "color:rgba(0,0,0,0.70); font-size:16px; line-height:24px;",
		"link" => "color:rgba(0,0,0,0.70); font-size:16px; line-height:24px;",

		"legenda" => "color:rgba(0,0,0,0.70); font-size:14px; line-height:20px;",
		"titulo" => "color:rgba(0,0,0,85); font-size:20px; font-weight:normal; line-height:30px;",

		"principal" => "color:rgba(0,0,0,85); font-size:24px; font-weight:bold; line-height:30px;",
	];

	const MODELO_CONTATO = '
		<!doctype html>
		<html lang="pt-br">
			<head>
				<meta charset="iso-8859-1">
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
				<base href="{{site.url}}">
				<title>{{cabecalho.titulo}} - {{site.nome}}</title>
			</head>
			<body style="width:100%; min-width:320px; height:100%; padding:0; margin:0; font-family:Helvetica Neue, Helvetica, Arial, serif; background:'.ModuloEmails::CSS["fundo-2"].';">
				<div style="background:'.ModuloEmails::CSS["cor-azul"].';">
					<div style="'.ModuloEmails::ELEM["envelope"].'">
						<a href="{{site.url}}" target="_blank" style="width:300px; height:80px; padding:0; margin:0; display:block; color:'.ModuloEmails::CSS["cor-azul"].'; text-decoration:none;">
							<img src="{{site.url}}img/logo-sixthsee.png" alt="Logotipo da {{site.nome}}" width="100%" height="100%">
						</a>
					</div>
				</div>
				<div style="'.ModuloEmails::ELEM["envelope"].'">
					<div>
						<h1 style="'.ModuloEmails::TEXTO["principal"].ModuloEmails::CSS["margin"].'">{{corpo.titulo}}</h1>
						<p style="'.ModuloEmails::TEXTO["legenda"].ModuloEmails::CSS["margin-p"].'">{{corpo.subtitulo}}</p>
					</div>
					<div style="'.ModuloEmails::ELEM["divisor"].ModuloEmails::CSS["margin-g"].'"></div>
					<div style="'.ModuloEmails::TEXTO["corpo"].ModuloEmails::CSS["margin-g"].'">
						{{corpo.miolo}}
					</div>
					<div style="'.ModuloEmails::ELEM["divisor"].ModuloEmails::CSS["margin-g"].'"></div>
					<div style="'.ModuloEmails::CSS["margin-g"].'">
						<p style="'.ModuloEmails::TEXTO["titulo"].ModuloEmails::CSS["margin"].'">{{site.nome}}</p>
					</div>
				</div>
			</body>
		</html>
	';

	public static function enviarEmailPadrao($tipo, $extras = [], $teste = 0) {
		$nomeEmpresa = NOME_SITE;
		$emailRemetente = ModuloEmails::REMETENTE;

		if ($tipo == 1) {
			$cabecalho = [
				"titulo" => "Contato pelo site"
			];
			$corpo = [
				"miolo" => "",
				"titulo" => "Contato pelo site",
				"subtitulo" => 'Mensagem através da página "'.$extras["nomePagina"].'"'
			];

			$assunto = "Contato pelo site";
			$corpoEmail = ModuloEmails::MODELO_CONTATO;

			$nome			= antiInjection($extras["nome"]);
			$email			= antiInjection($extras["email"]);
			$telefone		= antiInjection($extras["telefone"]);
			$empresa 		= antiInjection($extras["empresa"]);
			$servicos 		= antiInjection($extras["servicos"]);
			$descricao 		= antiInjection($extras["descricao"]);
			$diferenciais 	= antiInjection($extras["diferenciais"]);
			$concorrentes 	= antiInjection($extras["concorrentes"]);
			$expectativa	= antiInjection($extras["expectativa"]);
			$mensagem		= aspasPHP($extras["mensagem"]);

			if (!$nome) {
				return false;
			}

			$arrayCampos = array(
				"Nome" => $nome,
				"E-mail" => $email,
				"Telefone" => $telefone,
				"Nome da empresa" => $empresa,
				"Soluções de interesse" => $servicos,
				"Descrição do projeto" => $descricao,
				"Diferenciais da empresa" => $diferenciais,
				"Concorrentes" => $concorrentes,
				"Expectativa de resultado" => $expectativa,
				"Mensagem" => $mensagem,
			);

			foreach ($arrayCampos as $campo => $valor) {
				if (!$valor) {
					continue;
				}
				elseif ($campo == "Nome") {
					$corpo["miolo"] .= '
						<p style="'.ModuloEmails::TEXTO["titulo"].ModuloEmails::CSS["margin"].'">'.$valor.'</p>
						<div style="'.ModuloEmails::CSS["margin-m"].'"></div>
					';
				}
				else {
					$corpo["miolo"] .= '
						<p style="'.ModuloEmails::TEXTO["legenda"].ModuloEmails::CSS["margin-g"].'">'.$campo.'</p>
						<p style="'.ModuloEmails::TEXTO["corpo"].ModuloEmails::CSS["margin"].'">'.$valor.'</p>
					';
				}
			}

			$destino = $extras["destino"];
			$emailCopia = null; // Esse e-mail não vai com cópia
		}
		else {
			return false;
		}

		$trocarIsso = array(
			"{{cabecalho.titulo}}",

			"{{corpo.miolo}}",
			"{{corpo.titulo}}",
			"{{corpo.subtitulo}}",

			"{{site.url}}",
			"{{site.nome}}",
		);

		$porIsso = array(
			$cabecalho["titulo"],

			$corpo["miolo"],
			$corpo["titulo"],
			$corpo["subtitulo"],

			URL_SITE,
			NOME_SITE
		);

		// Substituir
		$corpoEmail = str_replace($trocarIsso, $porIsso, $corpoEmail);

		// Destino
		$destino = $destino ?: $emailCopia;

		// Vamos iniciar um objeto da classe PHPMailer e definir algumas coisas para o envio //
		$phpMailer = new PHPMailer();
		$phpMailer->SMTPDebug = false;
		$phpMailer->IsSMTP();
		$phpMailer->IsHTML(true);
		$phpMailer->CharSet    ='ISO-8859-1';
		$phpMailer->Encoding   = 'base64';
		$phpMailer->Host       = 'smtp.uni5.net';
		$phpMailer->SMTPAuth   = true;
		$phpMailer->Username   = "contato@6thsee.com.br";
		$phpMailer->Password   = '8y@yx4PdvzGd';
		$phpMailer->SMTPSecure = 'tls';
		$phpMailer->Port       = 587;
		$phpMailer->setFrom(ModuloEmails::REMETENTE, $nomeEmpresa);

		// Destinatário(s) //
		$arraySeparadores = [';', '/', '|', '-', ':'];
		$separadoVirgula = str_replace($arraySeparadores, ',', $destino); // garante que será separado por virgula //
		$pedacos = explode(",",$separadoVirgula);

		// Adiciona cada email como destinatário //
		foreach($pedacos as $pedaco){
			$phpMailer->AddAddress(trim($pedaco));
		}

		$phpMailer->Subject  = $assunto; // Assunto da mensagem //
		$phpMailer->Body     = $corpoEmail; // Corpo do email //

		// TESTE!
		if ($teste) {
			echo nl2br($corpoEmail);
			echo "<br>Destino: ".$destino;
			echo "<br>Remetente: $emailRemetente";
			echo "<br>Assunto: ".$assunto;
		}
		else {

			try{
				$enviar = $phpMailer->Send();
			}
			catch(Exception $e){
				echo($e->getMessage());
				$enviar = false;
			}

			return $enviar;
		}
	}
}

// converte todos os caracteres especiais html de um documento html e reorganiza ele em formato texto
function html2text($texto) {

	$novaLinha = "\r\n";
	//$novaLinha = "[r,n]"; // testes

	// cortar as tags que serao convertidos em novas linhas
	$trocarIsso = array("<br />", "<br/>", "<br>", "</p>", "</tr>", "</table>", "</div>", "</head>");
	$texto = str_ireplace($trocarIsso, $novaLinha, $texto); // str_ireplace eh igual a str_replace, soh que case insensitive

	// trocar os </td> por " - "
	$texto = str_ireplace("</td>", " - ", $texto);

	// trocar todos os caracteres especiais html("&aacute;", "&otilde;", etc.) por seu correspondente em lingua natural
	$texto = html_entity_decode($texto);

	// remover espaco no inicio e no fim
	$texto = trim($texto);
	// substituir qualquer non-space por um espaco
	$texto = preg_replace('/[\t]/', ' ', $texto);
	// remover espacos duploes
	$texto = preg_replace('/\s(?=\s)/', '', $texto);

	// trocar todos os " - \r\n" por \r\n, pq " - \r\n" quer dizer que era uma </td></tr> que foi convertida
	$texto = str_ireplace("- $novaLinha", $novaLinha, $texto);
	$texto = str_ireplace("-  $novaLinha", $novaLinha, $texto);

	// remover todo tipo de tags que sobraram
	$texto = strip_tags($texto);

	return $texto;
}

// Conversor de Acentos para Codigo HTML
function acentos2html($texto){
	$texto = str_replace("&", "&amp;", htmlentities($texto));
	return $texto;
}
?>