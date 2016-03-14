<?php
error_reporting(0);

$corpus = $_POST["corpus"];
$url = $_POST["url"];
if (isset($_FILES["texte"]) and $_FILES["texte"]["error"] == 0)
{
	if ($_FILES["texte"]["size"] <= 1000000)
	{
		$infosfichier = pathinfo($_FILES["texte"]["name"]);
		$extension_upload = $infosfichier["extension"];
		$extensions_autorisees = array("xml", "XML");
		if (in_array($extension_upload, $extensions_autorisees))
		{
			$name = $_FILES["texte"]["tmp_name"];
		}
	}}
elseif($url != "" )
	{$name = $url;}
elseif($corpus !=""){$name = $corpus;}
if (isset($name)){
	foreach ($name as $path){
		$xsl = new DOMDocument();
		$xsl->load("xsl/obvil.xsl");

		$inputdom = new DomDocument();
		$inputdom->load($path);
		$proc = new XSLTProcessor();
		$xsl = $proc->importStylesheet($xsl);
		// $proc->setParameter("", $parameters );
		$newdom = $proc->transformToDoc($inputdom);
		echo $newdom->SaveXML();}
}else{
	echo "Fichier non valide";
	}

?>	