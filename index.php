<?php

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(360);
include("functions.php");
$html = ""
	."<html>"
	."<head>"
	."<meta charset='UTF-8'/>"
	."<link href='style.css' rel='stylesheet' type='text/css'/>"
	."</head>"
	."<body>";
$form = <<<HTML
<div class="center">
  <h3>Générateur de tableaux d'occupation scénique</h3>
  <p>Veuillez sélectionner la ou les pièces à visualiser (maintenez la touche shift/majuscule enfoncée pour sélectionner plusieurs pièces) :</p>
  <form method="post" enctype="multipart/form-data">
        <select name="files[]" multiple>
			<option value='../../corpus/claudel/claudel_jeune-fille-violaine_1892.xml'>La Jeune Fille Violaine, 1892</option>";
			<option value='../../corpus/claudel/claudel_jeune-fille-violaine_1899.xml'>La Jeune Fille Violaine, 1899</option>";
			<option value='../../corpus/claudel/claudel_annonce_1911.xml'>L'Annonce faite à Marie, 1911</option>";
			<option value='../../corpus/claudel/claudel_annonce_1948.xml'>L'Annonce faite à Marie, 1948</option>";
		</select>
		<input type="hidden" name="post"/>
	    <input type="submit" value="Valider" />
  </form>
</div>
HTML;

$legend = <<<HTML
	    <br/>
        <br/>
        <table class="legend">
          <tr>
            <td class="speaking configuration">1</td>
            <td>Personnage présent </td>
          </tr>
          <tr>
            <td class="mute configuration">2</td>
            <td>Personnage muet </td>
          </tr>

          <tr>
            <td class="dead configuration">†</td>
            <td>Personnage mort ou évanoui</td>
          </tr>
          <tr>
            <td class="hidden configuration">c</td>
            <td>Personnage caché </td>
          </tr>
          <tr>
            <td class="offstage configuration">1</td>
            <td>Personnage hors-scène </td>
          </tr>
          <tr>
            <td class="monolog configuration">1</td>
            <td>Monologue </td>
          </tr>
          <tr>
            <td class="dialog configuration">2</td>
            <td>Dialogue </td>
          </tr>
          <tr>
            <td class="trilog configuration">3</td>
            <td>Trilogue </td>
          </tr>
<tr>
            <td class="configuration">+</td>
            <td>Plus de trois personnages </td>
          </tr>

        </table>

HTML;
$html .= $form;
if(isset($_POST["post"])){
	$files=$_POST["files"];
	foreach($files as $file){
		$xsl = new DOMDocument();
		$xsl->load("table.xsl");
		$inputdom = new DomDocument();
		$inputdom->load($file);
		$proc = new XSLTProcessor();
		$xsl = $proc->importStylesheet($xsl);
		$newdom = $proc->transformToDoc($inputdom);
		$html .= $newdom->SaveXML();
		
	}
	$html .= $legend;
}
$html.="</body>"
	."</html>";
echo $html;
?>