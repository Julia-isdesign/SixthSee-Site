<?php
# Blocos
foreach ($blocos as $i => $bloco) {

	# # Título
	if ($bloco["tipo"] == "titulo") {
		if (!in_array($bloco["tipoConteudo"], ["h1", "h2", "h3", "h4", "h5", "h6"])) {
			$tamanho = "h3";
		}
		else {
			$tamanho = $bloco["tipoConteudo"];
		}

		$titulo = [
			"tamanho" => $tamanho,
			"conteudo" => $bloco["conteudo"],
		];
?>
<div class="l-bloco l-bloco--titulo">
	<?php include("includes/blocos/titulo.php"); ?>
</div>
<?php
	}

	# # Texto
	else if ($bloco["tipo"] == "texto") {
		$classe = "";

		if ($bloco["tipoConteudo"] == 2) {
			$classe .= "b-paragrafo--x ";
		}
		if ($bloco["posConteudo"] == 2) {
			$classe .= "b-paragrafo--i ";
		}

		$imagem = [
			"file" => "",
			"legenda" => "",
		];

		$bloco["conteudo"] = str_replace(
			[ "<table", "</table>" ],
			[ '<div class="b-paragrafo__tabela"><table', "</table></div>" ],
			$bloco["conteudo"]
		);

		if ($bloco["icone"]) {
			$consulta = consultar(
				"SELECT file, legenda, proporcao
				   FROM arquivos
				  WHERE pai = 'blocos' AND idPai = ".$bloco["id"]." LIMIT 1"
			);
			$imagem = $consulta["status"] ? $consulta["dados"][0] : false;
		}

		$paragrafo = [
			"id" => $bloco["id"],
			"classe" => $classe,
			"conteudo" => $bloco["conteudo"],
			"imagem" => $imagem["file"] ? true : false,
			"imagem-url" => $imagem["file"],
			"imagem-legenda" => $imagem["legenda"] ? true : false,
			"imagem-texto" => $imagem["legenda"],
			"imagem-proporcao" => $imagem["proporcao"] ? 100 / $imagem["proporcao"] : "",
		];
?>
<div class="l-bloco">
	<?php include("includes/blocos/paragrafo.php"); ?>
</div>
<?php
	}

	# # Destaque
	else if ($bloco["tipo"] == "destaque") {
		$destaque = [
			"conteudo" => $bloco["conteudo"],
			"legenda" => $bloco["link"],
		];
?>
<div class="l-bloco">
	<?php include("includes/blocos/destaque.php"); ?>
</div>
<?php
	}

	# # Lista
	else if ($bloco["tipo"] == "lista") {
		$consulta = consultar(
			"SELECT icone, titulo, texto, link
			   FROM blocos_itens
			  WHERE idBloco = ".$bloco["id"]."
			  ORDER BY pos ASC "
		);

		if (count($consulta["dados"]) > 0) {
			$lista = [];

			foreach ($consulta["dados"] as $item) {
				if (!strpos($item["link"], parse_url(URL_SITE, PHP_URL_HOST)) && $item["link"]) {
					$target = "_blank";
				}
				else {
					$target = "_self";
				}

				array_push($lista, [
					"marcador" => true,
					"marcador-tipo" => $bloco["conteudo"] == 2 ? "mi" : "",
					"marcador-conteudo" => $item["icone"],
					"texto-titulo" => $item["titulo"],
					"texto-legenda" => $item["texto"],
					"link" => $item["link"] ? true : false,
					"link-url" => $item["link"],
					"link-target" => $target,
				]);
			}
?>
<div class="l-bloco">
	<?php include("includes/blocos/lista.php"); ?>
</div>
<?php
		}
	}

	# # Grade
	else if ($bloco["tipo"] == "grade") {
		$consulta = consultar(
			"SELECT icone, titulo, texto, link
			   FROM blocos_itens
			  WHERE idBloco = ".$bloco["id"]."
			  ORDER BY pos ASC"
		);

		if (count($consulta["dados"]) > 0) {
			$grade = [];

			foreach ($consulta["dados"] as $item) {
				if (!strpos($item["link"], parse_url(URL_SITE, PHP_URL_HOST)) && $item["link"]) {
					$target = "_blank";
				}
				else {
					$target = "_self";
				}

				array_push($grade, [
					"marcador" => true,
					"marcador-tipo" => $bloco["conteudo"] == 2 ? "mi" : "",
					"marcador-conteudo" => $item["icone"],
					"texto-titulo" => $item["titulo"],
					"texto-legenda" => $item["texto"],
					"link" => $item["link"] ? true : false,
					"link-url" => $item["link"],
					"link-target" => $target,
				]);
			}
?>
<div class="l-bloco">
	<?php include("includes/blocos/grade.php"); ?>
</div>
<?php
		}
	}

	# # Galeria
	else if ($bloco["tipo"] == "galeria") {
		$consulta = consultar(
			"SELECT file, ".tamanhosToCase(["p", "m", "g"])." as tamanho, legenda
			   FROM arquivos
			  WHERE pai = 'blocos' AND idPai = ".$bloco["id"]."
			  ORDER BY pos ASC"
		);

		if (count($consulta["dados"]) > 0) {
			$galeria = [];

			foreach ($consulta["dados"] as $foto) {
				array_push($galeria, [
					"id" => $bloco["id"],
					"legenda" => $foto["legenda"] ?: $pagina["titulo"],
					"url" => $foto["file"],
				]);
			}
?>
<div class="l-bloco">
	<?php
	if ($url == "portfolio") {
		include("includes/blocos/galeria-portfolio.php");
	}
	else {
		include("includes/blocos/galeria.php");
	}
	?>
</div>
<?php
		}
	}

	# # Midia
	else if ($bloco["tipo"] == "midia") {
		if ($bloco["tipoConteudo"] == 1) {
			$imagem = consultar(
				"SELECT file, legenda, proporcao
				   FROM arquivos
				  WHERE pai = 'blocos'
				    AND idPai = ".$bloco["id"]." LIMIT 1"
			);
			$imagem = $imagem["dados"][0];

			$midia = [
				"tipo" => "imagem",
				"url" => $imagem["file"],
				"proporcao" => $imagem["proporcao"] ? 100 / $imagem["proporcao"] : "",
				"legenda" => $imagem["legenda"] ? true : false,
				"texto" => $imagem["legenda"] ?: $pagina["titulo"],
			];
		}
		else if ($bloco["tipoConteudo"] == 2) {
			$video = consultar(
				"SELECT file, legenda, ext
				   FROM arquivos
				  WHERE pai = 'blocos'
				    AND idPai = '{$bloco["id"]}'"
			);
			$video = $video["dados"][0];

			$midia = [
				"tipo" => "video",
				"url" => $video["file"],
				"extensao" => $video["ext"],
				"legenda" => $video["legenda"] ? true : false,
				"texto" => $video["legenda"] ?: $pagina["titulo"],
			];
		}
		else if ($bloco["tipoConteudo"] == 3) {
			$midia = [
				"tipo" => "mapa",
				"url" => "https://www.google.com/maps/embed/v1/place?key=".GOOGLE_MAPS_KEY."&q=".str_replace(" ", "+", $bloco["link"]),
				"legenda" => $bloco["conteudo"] ? true : false,
				"texto" => $bloco["conteudo"],
			];
		}
		else if ($bloco["tipoConteudo"] == 4) {
			$midia = [
				"tipo" => "youtube",
				"url" => urlDoVideo($bloco["link"], "embed"),
				"legenda" => $bloco["conteudo"] ? true : false,
				"texto" => $bloco["conteudo"],
			];
		}
		else if ($bloco["tipoConteudo"] == 5) {
			$audio = consultar(
				"SELECT file, legenda, ext
				   FROM arquivos
				  WHERE pai = 'blocos'
				    AND idPai = '{$bloco["id"]}'"
			);
			$audio = $audio["dados"][0];

			$midia = [
				"tipo" => "audio",
				"url" => $audio["file"],
				"extensao" => $audio["ext"],
				"legenda" => $audio["legenda"] ? true : false,
				"texto" => $audio["legenda"] ?: $pagina["titulo"],
			];
		}
		else if ($bloco["tipoConteudo"] == 6) {
			$midia = [
				"tipo" => "soundcloud",
				"url" => str_replace('&quot;', '"', $bloco["link"]),
				"legenda" => $bloco["conteudo"] ? true : false,
				"texto" => $bloco["conteudo"],
			];
		}
?>
<div class="l-bloco l-bloco--midia">
	<?php include("includes/blocos/midia.php"); ?>
</div>
<?php
	}

	# # Botão
	else if ($bloco["tipo"] == "botao") {
		$botao = [
			"tipo" => "link",
			"link-url" => $bloco["link"],
			"link-target" => $bloco["tipoConteudo"],
			"classe" => "",
			"icone" => $bloco["icone"],
			"conteudo" => $bloco["conteudo"],
		];

		switch ($bloco["estilo"]) {
			case 0:
				$botao["classe"] = "c-botao";
				break;
			case 1:
				$botao["classe"] = "c-botao--borda";
				break;
			case 2:
				$botao["classe"] = "c-botao--sombra";
				break;
			case 3:
				$botao["classe"] = "c-botao--x";
				break;
		}

		if ($blocos[$i - 1]["tipo"] !== "botao") {
?>
<div class="l-bloco">
	<div class="c-botoes">
<?php 
		}

		include("includes/blocos/botao.php");

		if ($blocos[$i + 1]["tipo"] !== "botao") {
?>
	</div>
</div>
<?php
		}
	}

	# # Arquivo
	else if ($bloco["tipo"] == "arquivo") {
		$consulta = consultar(
			"SELECT file, legenda, ext
			   FROM arquivos
			  WHERE pai = 'blocos'
			    AND idPai = ".$bloco["id"]
		);
		$consulta = $consulta["dados"][0];

		$arquivo = [
			"url" => URL_ARQUIVOS.$consulta["file"].".".$consulta["ext"],
			"icone" => $bloco["icone"],
			"titulo" => $bloco["conteudo"],
			"extensao" => $consulta["ext"],
		];

		if ($blocos[$i - 1]["tipo"] !== "arquivo") {
?>
<div class="l-bloco">
	<div class="b-arquivos">
	<?php 
	}

	include("includes/blocos/arquivo.php"); 

	if ($blocos[$i + 1]["tipo"] !== "arquivo") {
?>
	</div>
</div>
<?php
		}
	}

}
?>