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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="RGraph/libraries/RGraph.common.core.js"></script>
<script src="RGraph/libraries/RGraph.bar.js"></script>
<script type="text/javascript">var configurationBreaks = [];
var configurationLength = [];</script>
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
            <td class="aside configuration mute">2</td>
            <td>Personnage de l'espace secondaire </td>
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
        </table>
		<table class="legend">
          <tr>
          	<td class="configuration"></td>
          	<td class="configuration"></td>
          	<td>Scènes liées entre elles</td>
          </tr>
		  <tr>
		  	<td class="configuration"></td>
		  	<td class="configuration break"></td>
		  	<td>Rupture de la liaison des scènes</td>
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
		$code = str_replace(".xml", "", basename($file));
		$proc -> setParameter("", "basename", $code);
		$newdom = $proc->transformToDoc($inputdom);
		$html .= "<div>";
		$html .= "<div>";
		$html .=$newdom->SaveXML();
		$html .= "</div>";

		$pdo = new PDO('sqlite:../../corpus/claudel/claudel.sqlite');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$play = $pdo->query("SELECT id FROM play WHERE code = '".$code."'")->fetch();
		$play = $play['id'];
		$configurations = $pdo->query("SELECT c FROM configuration WHERE play = '".$play."' ORDER BY id")->fetchAll();
		$html .= "<script type='text/javascript'>var configurationLengthPlay = [];";
		foreach($configurations as $configuration){
			$html .= "configurationLengthPlay.push('".$configuration['c']."');";
		}
		$html .= "configurationLength.push(configurationLengthPlay);</script>";
		$html .= "<div width='600'>";
		$html .= "<canvas id='graph".$code."' width='600' height='200'>[No canvas support]</canvas><p>Nombre moyen de personnages présents sur scène (sans compter les personnages muets)
</p>";
		$html .="</div>";
		$html .= "</div>";

	}
	$html .= $legend;
}
$html.="<script type='text/javascript' src='breaks.js'></script>

</body>"
	."</html>";
echo $html;
?>