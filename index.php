<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<div class="center">
  <h3>Générateur de tableaux d'occupation scénique</h3>
  <p>Veuillez sélectionner la ou les pièces à visualiser (maintenez la touche shift/majuscule enfoncée pour sélectionner plusieurs pièces) :</p>
<form action="result.php" method="post" enctype="multipart/form-data">
<input type="file" name="texte" />
<p>ou entrer son URL :</p>
<input type="text" name="url"/><br/>
<p>ou sélectionner un fichier dans la liste :</p>
<?php
$files = glob("documents/*.xml");
if (empty($files)) {$files = (glob("../../../theatre-classique/code/documents/*.xml"));}
?>
<select name="corpus[]" multiple>
	<?php
/*
foreach ($files as $file){
	$name = realpath($file);
	echo "<option value='".$name."'>".basename($file)."</option>";
}
*/

// $files = glob("../../corpus/moliere/documents/*.xml");
foreach ($files as $file){
	$path = realpath($file);
	$name = basename($file);
	$text = file_get_contents($path);
	$text = preg_replace("#\s<\?xml-model .*\?>#", "", $text);
	$xml = simplexml_load_string($text);
	$xml->registerXPathNamespace('t', 'http://www.tei-c.org/ns/1.0');
	$title = $xml->xpath("//t:title");
	$title = $title[0];
	$title = (string)$title;
	$author = $xml->xpath("//t:author");
	$author = $author[0];
	$author = (string)$author;
	$div2 = $xml->xpath("//t:div2[@type='scene']");
	if ($div2){
		echo "<option value='".$path."'>".$author.", ".$title."</option>";
	}
}
?>
</select>
<br/><br/>
<input type="submit" value="Valider" /><br/><br/>
</form>
<p>Le texte doit respecter le schéma suivant :</p>
<textarea rows="15" cols="70" readonly><!ELEMENT TEI (teiHeader)>
<!ELEMENT teiHeader (fileDesc)>
<!ELEMENT fileDesc (titleStmt)>
<!ELEMENT titleStmt (title)>
<!ELEMENT title (#PCDATA)>
<!ELEMENT role (#PCDATA)><!--le nom de chaque personnage dans la liste des personnages en t&ecirc;te de la pièce-->
<!ATTLIST role
  xml:id NMTOKEN #REQUIRED><!--l'identifiant du personnage dans la liste des personnages en t&ecirc;te de la pièce-->
<!ELEMENT div1 (div2)><!--le contenu de chaque acte-->
<!ATTLIST div1
  type CDATA #REQUIRED><!--"acte" ou "act"-->
<!ELEMENT div2 (stage,sp)><!--le contenu de chaque scène-->
<!ELEMENT stage (#PCDATA)><!--la liste des personnages présents, séparés par des virgules-->
<!ELEMENT sp (#PCDATA)><!--le contenu de chaque réplique-->
<!ATTLIST sp
  who NMTOKEN #REQUIRED><!--l'identifiant du personnage qui parle--></textarea><br/>

<h4><a href="claudel">Corpus Claudel</a></h4>
  <form method="post" enctype="multipart/form-data" action="claudel/index.php">
        <select name="files[]" multiple>
			<option value='../../../corpus/claudel/claudel_jeune-fille-violaine_1892.xml'>La Jeune Fille Violaine, 1892</option>";
			<option value='../../../corpus/claudel/claudel_jeune-fille-violaine_1899.xml'>La Jeune Fille Violaine, 1899</option>";
			<option value='../../../corpus/claudel/claudel_annonce_1911.xml'>L'Annonce faite à Marie, 1911</option>";
			<option value='../../../corpus/claudel/claudel_annonce_1948.xml'>L'Annonce faite à Marie, 1948</option>";
		</select>
		<input type="hidden" name="post"/><br/><br/>
	    <input type="submit" value="Valider" />
  </form>

</div>
</body>