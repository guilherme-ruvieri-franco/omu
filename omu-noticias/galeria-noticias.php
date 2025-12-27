<?php
$dirNoticias = __DIR__;

$arquivos = glob($dirNoticias . '/*.html');

usort($arquivos, function($a, $b) {
    return filemtime($b) <=> filemtime($a);
});
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>OMU - HOME</title>
</head>
<link rel="shortcut icon" type="imagex/png" href="elementos_visuais\icones\icone.ico">
<link href="https://fonts.cdnfonts.com/css/nimbus-sans-l" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;1,300&display=swap" rel="stylesheet">
<link href="css\css-galeria-noticias.css" rel="stylesheet">

<body>
  <header class="cabecalho">
    <a class="link-logo-omu" href="../index.html"><img class="logo-omu" src="elementos_visuais\logos\logo-omu-completo.png"
        alt="Logo da OMU"></a>
    <div class="menu-navegacao">
      <a class="l1" href="../sobre.html">sobre</a>
      <a class="l2" href="../blog.html">blog</a>
      <a class="l3" href="../edicao-atual.html">41ª edição</a>
      <a class="l1" href="../edicoes.html">edições</a>
      <a class="l2" href="../imprensa.html">imprensa</a>
      <a class="l3" href="../recomendacoes.html">indicações</a>
      <a class="l1" href="../suporte.html">suporte</a>
      <a class="link-login" href="../https://prova.omu.preface.com.br/prova/" target="self">login</a>
    </div>
  </header>

  <div class="galeria-polaroid">
<?php
    $contador = 0;

    foreach ($arquivos as $arquivoCompleto) {
        $nomeArquivo = basename($arquivoCompleto);
        // 🔴 AQUI entra o filtro da OPÇÃO 2:
        // se o arquivo NÃO começar com "noticia", pula para o próximo
        if (!preg_match('/^noticia/i', $nomeArquivo)) {
            continue;
        }

        $urlNoticia = $nomeArquivo;

        // valores padrão
        $manchete  = 'Notícia';
        $legenda   = '';
        $srcImagem = 'elementos_visuais/fotos/anne-bronzi.png';

        // lê o HTML da notícia
        $html = file_get_contents($arquivoCompleto);
        if ($html !== false) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $xpath = new DOMXPath($dom);

            // imagem: <div class="foto-manchete"><img ...></div>
            $imgNode = $xpath->query("//div[contains(@class,'foto-manchete')]//img")->item(0);
            if ($imgNode) {
                $srcImagem = $imgNode->getAttribute('src');
            }

            // manchete: <p class="manchete">...</p>
            $mancheteNode = $xpath->query("//p[contains(@class,'manchete')]")->item(0);
            if ($mancheteNode) {
                $manchete = trim($mancheteNode->textContent);
            }

        }

        // abre uma nova linha a cada 3 cards (se quiser manter esse layout)
        $contador++;
        if ($contador % 3 == 1) {
            echo '<div class="linha-noticias">';
        }
  ?>
        <a class="polaroid-manchete" href="<?php echo htmlspecialchars($urlNoticia); ?>">
            <img  class="foto" src="<?php echo htmlspecialchars($srcImagem); ?>" alt="<?php echo htmlspecialchars($manchete); ?>">
            <p class="manchete"> <?php echo htmlspecialchars($manchete); ?> </p>
        </a>
        
  <?php
        // fecha a linha a cada 3 cards
        if ($contador % 3 == 0) {
            echo '</div>';
        }
    }

    // se a última linha não fechou (não múltiplo de 3), fecha aqui
    if ($contador % 3 != 0) {
        echo '</div>';
    }
  ?>
</div>

  </div>
  </body>
  </html>