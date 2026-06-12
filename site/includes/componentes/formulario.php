<?php require_once("includes/manipuladores/formulario.php"); ?>
<section class="l-bloco">
	<header class="l-bloco l-bloco--titulo">
		<h3 class="f-titulo f-titulo--h3">Formulário de contato</h3>
	</header>
	<div class="l-bloco l-bloco--titulo">
		<div class="f-wysiwyg">
			<p>Entre em contato conosco. Responderemos assim que possível.</p>
		</div>
	</div>
	<div class="l-bloco">
		<form class="c-form" id="form-padrao">
			<input type="hidden" name="idPai" value="<?=$pagina["id"]?>">
			<?php
			form([
				"label" => "Nome completo",
				"type" => "text",
				"id" => "nome",
				"placeholder" => "Ex.: João da Silva",
				"validate" => true,
				"min" => "name",
				"max" => 256,
				"message" => "Informe nome e sobrenome",
			]);
			form([
				"label" => "E-mail de contato",
				"type" => "email",
				"id" => "email",
				"placeholder" => "Ex.: joao.silva@email.com",
				"validate" => true,
				"min" => "email",
				"max" => 256,
				"message" => "Informe um e-mail válido",
			]);
			form([
				"spacer" => "c-form__espacador--m",
				"label" => "Telefone / WhatsApp",
				"type" => "tel",
				"id" => "telefone",
				"placeholder" => "Ex.: (55) 98765-4321",
				"validate" => true,
				"min" => 14,
				"max" => 15,
				"mask" => "phone",
				"message" => "Informe um telefone válido",
			]);

			if ($pagina["form"] == 2) {
				form([
					"label" => "Mensagem",
					"type" => "textarea",
					"id" => "mensagem",
					"placeholder" => "Escreva sua mensagem",
					"validate" => true,
					"min" => 10,
					"max" => 480,
					"message" => "Informe a mensagem que ser enviada",
				]);
			}

			form([
				"label" => "Consentimento",
				"type" => "checkbox",
				"id" => "consentimento",
				"validate" => true,
				"min" => "checkbox",
				"message" => "É preciso consentir com o tratamento dos dados",
				"options" => [
					1 => "Concordo que ".NOME_SITE." armazene os dados do formulário."
				],
			]);
			?>
			<p class="c-form__aviso" id="form-padrao-aviso">
				<span class="c-form__aviso__texto" id="form-padrao-aviso-texto"></span>
				<i class="c-form__aviso__icone | a-mi" id="form-padrao-aviso-icone" aria-hidden="true"></i>
			</p>
			<div class="c-form__espacador">
				<button type="submit" class="c-botao" id="form-padrao-botao">
					<i class="c-botao__icone | a-mi" aria-hidden="true">send_message</i>
					<span class="c-botao__texto">Enviar</span>
				</button>
			</div>
		</form>
	</div>
</section>