<?php

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(360);
$form = ""
	."<html>"
	."<head>"
	."<meta charset='UTF-8'/>"
	."</head>"
	."<body>";
$form .= <<<HTML
<div class="center">
  <h3>Visualisation de la dynamique des échanges</h3>
  <p>Veuillez sélectionner la pièce à visualiser :</p>
  <form method="post" enctype="multipart/form-data">
        <select name="files[]">
			<option value='../../../corpus/claudel/claudel_jeune-fille-violaine_1892.xml'>La Jeune Fille Violaine, 1892</option>";
			<option value='../../../corpus/claudel/claudel_jeune-fille-violaine_1899.xml'>La Jeune Fille Violaine, 1899</option>";
			<option value='../../../corpus/claudel/claudel_annonce_1911.xml'>L'Annonce faite à Marie, 1911</option>";
			<option value='../../../corpus/claudel/claudel_annonce_1948.xml'>L'Annonce faite à Marie, 1948</option>";
		</select>
		<input type="hidden" name="post"/>
	    <input type="submit" value="Valider" />
  </form>
</div>
HTML;
$form .= "</body></html>";
if(isset($_POST["post"])){
	$files=$_POST["files"];
	foreach($files as $file){
		$xsl = new DOMDocument();
		$xsl->load("Teinte/tei2html.xsl");
		$inputdom = new DomDocument();
		$inputdom->load($file);
		$proc = new XSLTProcessor();
		$xsl = $proc->importStylesheet($xsl);
		$newdom = $proc->transformToDoc($inputdom);
		$html =$newdom->SaveXML();
	}
}else{
	$html = $form;
}
echo $html;
?>