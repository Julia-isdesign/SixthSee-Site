<?php
/* Copyright © isDesign Estúdio de Criação Digital [2021] ** www.isdesign.com.br */

/**
 * Ver se esse nome já existe
 *
 * @param string $nomeImagem nome desejado
 * @param string $tabela tabela onde quer verificar se já não há o mesmo nome
 * @param string $campo coluna que irá verificar o nome
 * @param int $idIgnorar id que a query irá descartar
 *
 * @return string o primeiro nome valido. Ex: arquivo.jpg|arquivo_3.jpg
 */
function nomeImagemValido($nomeImagem, $tabela, $campo = "file", $idIgnorar = 0){

    $campo = $campo ? $campo : "file";

    // pesquisar no BD
    $where = "$campo = '$nomeImagem'";
    if ($idIgnorar) {
        $where .= " AND id<>$idIgnorar ";
    }

    $consulta = consultar("SELECT id FROM $tabela WHERE $where", 0);
    if (count($consulta["dados"]) > 1) {
        
        // Nome sem extensão
        $nomeSEx = $nomeImagem;

        // ver se tem underline
        if (strstr($nomeSEx, '_') !== false) {
            $vetor_string = explode("_", $nomeSEx);
            $nomeSEx      = $vetor_string[0];
            $numero       = (int) $vetor_string[1];
        } 
        
        else {
            $numero = 0;
        }

        // acrescentar 1 ao número
        $numero++;

        // definir o novo nome
        $nomeImagem = $nomeSEx . "_" . $numero;

        // ver se esse nome já existe
        return nomeImagemValido($nomeImagem, $tabela, $campo, $idIgnorar);

    } 
    
    else {
        return $nomeImagem;
    }
}

/**
 * Retorna a extensao baseado no tipo de arquivo
 */
function extensaoPadrao($tipo)
{
    $tiposDeArquivo = [
        // Tipo de arquivo
        ".doc" => ["application/msword", "application/doc", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        ".txt" => ["text/plain"],
        ".ppt" => ["application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation"],
        ".pdf" => ["application/pdf"],
        ".xls" => ["application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"],
        ".zip" => ["application/zip", "application/binary", "application/x-zip-compressed", "application/octet-stream"],
        // Imagens
        ".jpg" => ["image/pjpeg", "image/jpeg"],
        ".png" => ["image/x-png", "image/png"],
        ".gif" => ["image/gif"],
        ".swf" => ["application/x-shockwave-flash"],
    ];

    $retorno = "";

    foreach ($tiposDeArquivo as $extensao => $tipos) {
        if (in_array($tipo, $tipos)) {
            $retorno = $extensao;
        }
    }

    return $retorno;
}

/**
 * Retorna o icone baseado no tipo de arquivo
 */
function iconeArquivo($arquivo)
{
    $extensao       = strtolower(end(explode(".", $arquivo)));
    $tiposDeArquivo = [
        // Tipo de arquivo
        "doc" => ["doc", "txt", "docx"],
        "ppt" => ["ppt"],
        "pdf" => ["pdf"],
        "xls" => ["xls"],
        "img" => ["jpg", "png", "gif", "jpeg"],
    ];

    // Por padrão retorna o ícone de Zip
    $retorno = "zip";

    foreach ($tiposDeArquivo as $icone => $tipos) {
        if (in_array($extensao, $tipos)) {
            $retorno = $icone;
        }
    }

    return $retorno;
}

/**
 * Traduzir o link de um video para um formato mais util
 *
 * @param string $urlDoVideo link do video
 * @param string $extrair oque quer extrair dele. player|url|img
 *
 * @return string retorna o que foi pedido para extrair ou o link novamente
 */
function urlDoVideo($urlDoVideo, $extrair = 'player')
{

    // é um vídeo do youtube
    if (strstr($urlDoVideo, "youtu") !== false) {

        // pegar somente o id do vídeo em links normais
        if (strstr($urlDoVideo, 'watch?v=') != false) {
            $vetor_string = explode("watch?v=", $urlDoVideo);
            $urlDoVideo   = $vetor_string[1];
        }
        // pegar somente o id do vídeo em links curtos
        if (strstr($urlDoVideo, 'youtu.be/') != false) {
            $vetor_string = explode("youtu.be/", $urlDoVideo);
            $urlDoVideo   = $vetor_string[1];
        }
        // pegar somente o id do vídeo em links curtos
        if (strstr($urlDoVideo, 'embed/') != false) {
            $vetor_string = explode("embed/", $urlDoVideo);
            $urlDoVideo   = $vetor_string[1];
        }
        // tirar o feature ou qualquer coisa assim.
        if (strstr($urlDoVideo, '&') != false) {
            $vetor_string = explode("&", $urlDoVideo);
            $urlDoVideo   = $vetor_string[0];
        }
        if (strstr($urlDoVideo, '#') != false) {
            $vetor_string = explode("#", $urlDoVideo);
            $urlDoVideo   = $vetor_string[0];
        }
        if ($extrair == 'player') {
            return '<iframe src="https://www.youtube.com/embed/' . $urlDoVideo . '?rel=0" frameborder="0" style="width:100%; height:100%; position:absolute;" allowfullscreen></iframe>';
        } else if ($extrair == 'url') {
            return $urlDoVideo;
        } else if ($extrair == 'imagem') {
            return 'https://i1.ytimg.com/vi/' . $urlDoVideo . '/mqdefault.jpg';
        } else {
            return 'https://www.youtube.com/embed/' . $urlDoVideo;
        }
    }
    // é um vídeo do Facebook.
    else if (strstr($urlDoVideo, "facebook") !== false || strstr($urlDoVideo, "fb.com") !== false) {
        $string = "<div id='fb-root'></div>
					<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id))
						return;  js = d.createElement(s); js.id = id;
						js.src = '//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.3';
						fjs.parentNode.insertBefore(js, fjs);
					}
					(document, 'script', 'facebook-jssdk'));
					</script>
					<div class='fb-video' data-allowfullscreen='1' data-href='$urlDoVideo'>
						<div class='fb-xfbml-parse-ignore'>
							<blockquote cite='$urlDoVideo'>
								<a href='$urlDoVideo'></a>
							</blockquote>
						</div>
					</div>";
        return $string;
    }
}

/**
 * Traduzir o link de um áudio do soundcloud para um formato mais util
 *
 * @param string $urlDoAudio link do video
 * @param string $extrair oque quer extrair dele. player|url
 *
 * @return string retorna o que foi pedido para extrair ou o link novamente
 */
function playerSoundCloud($urlDoAudio, $extrair = 'player')
{

    // pegar somente o usuário e a músiva
    $vetor_string = explode("soundcloud.com/", $urlDoAudio);
    $urlDoAudio   = $vetor_string[1];
    
    // tirar qualquer coisa inútil que vier na url.
    if (strstr($urlDoAudio, '&') != false) {
        $vetor_string = explode("&", $urlDoAudio);
        $urlDoAudio   = $vetor_string[0];
    }
    if (strstr($urlDoAudio, '#') != false) {
        $vetor_string = explode("#", $urlDoAudio);
        $urlDoAudio   = $vetor_string[0];
    }
    if ($extrair == 'player') {
        return '<iframe width="100%" height="300" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//soundcloud.com/' . $urlDoAudio . '&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>';
    } 
    else if ($extrair == 'url') {
        return "https://soundcloud.com/".$urlDoAudio;
    } 

}

/**
 * Retorna qual o arquivo existente para um file da informação
 * @param string $pai   Qual o pai do file
 * @param string $idPaiCampo    Qual o campo que identifica o id do pai na consulta
 * @param array $tamanhos       Tamanhos que a imagem pode aparecer, na ordem de preferencia. O tamanho original sempre será usado se não encontrar nenhuma imagem mini
**/
function getFileBD($pai, $idPaiCampo, $tamanhos = ["pp", "p", "m", "g", "gg"], $idArquivo = false){

    $tamanho_select = tamanhosToCase($tamanhos);

    $WHERE = $idArquivo !== false ? "a.id = $idArquivo" : "a.pai = '$pai' AND a.idPai = $idPaiCampo";

    return "
        (
            SELECT CONCAT(a.file, $tamanho_select) FROM arquivos a WHERE $WHERE LIMIT 1
        )
    ";
}

/**
 * retorna o primeiro tamanho que exista
**/
function tamanhosToCase($tamanhos = ["pp", "p", "m", "g", "gg"], $alias = ""){
    $tamanhos = array_filter($tamanhos, function($v) {
        return in_array($v, ["pp", "p", "m", "g", "gg"]); // Garante que não foi passado nenhum tamanho não suportado pelo BD
    });

    if(count($tamanhos) > 0){
        $tamanho_select = "
            CASE
        ";

        foreach($tamanhos as $tamanho){
            $tamanho_select .= " WHEN ".$alias."$tamanho = 1 THEN '-$tamanho'";
        }

        $tamanho_select .= "
                ELSE ''
            END
        ";
    }

    else $tamanho_select = "''";

    return $tamanho_select;
}

/**
 * Definir/formatar o tamanho da imagem
 *
 * @param string $imagemOriginal caminho da imagem
 * @param string $ehWebp se a imagem possui sua vesão WEBP
 * @param array $parametrosFormatarImagem  parâmetros (em forma de array) que serão passados para a função formatarImagem
 * @param bool $slider Se é um slider
 *
 * @return array [Imagem tratada com formatarImagem ou WEBP, Caminho informado nos parametros]
 */
function formatarImagemWebp($imagemOriginal, $ehWebp, $parametrosFormatarImagem = [], $slider = false)
{

    $parametros = array_merge([$imagemOriginal], (array) $parametrosFormatarImagem);

    $imagem = call_user_func_array('formatarImagem', $parametros);

    $ehChrome = qualNavegador(true) == "Google Chrome";

    if ($ehChrome && $ehWebp) {
        $imagem['src'] = substr($imagem['src'], 0, -strlen(end(explode('.', $imagem['src'])))) . "webp";
    } else if ($slider) {
        $imagem['src'] = $imagem['src'];
    } else {
        $imagem['src'] = URL_SITE . $imagem['src'];
    }

    return [$imagem, $imagemOriginal];
}

/**
 * função que traz o tamanho da imagem redimensionada
 * usada para informarmos ao Fbok o tamanho correto da miniatura
**/
function proporcaoImagem($tamanhoOriginal, $tamanhoDesejado="pp"){

    //echo "tamanhoOriginal: ".$tamanhoOriginal." / tamanhoDesejado: ".$tamanhoDesejado;

    $tamanhoDesejado = str_replace("-", "", $tamanhoDesejado);
    $proporcao = explode("x", $tamanhoOriginal);

    // descobrir se é H ou W
    if($proporcao[0]>$proporcao[1]){ // é W
        $calc = $proporcao[1] * DIMENSOES_IMAGENS[$tamanhoDesejado] / $proporcao[0];
        $tamanhoFinal = DIMENSOES_IMAGENS[$tamanhoDesejado]."x".(int)$calc;
    } 
    else { // é H
        $calc = $proporcao[0] * DIMENSOES_IMAGENS[$tamanhoDesejado] / $proporcao[1];
        $tamanhoFinal = (int)$calc."x".DIMENSOES_IMAGENS[$tamanhoDesejado];
    }
    return $tamanhoFinal;
}
