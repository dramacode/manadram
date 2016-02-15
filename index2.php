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
  <p>Veuillez sélectionner la ou les pièces à visualiser :</p>
  <form method="post" enctype="multipart/form-data">
        <select name="files[]" multiple>
			<option value='claudel/claudel_jeune-fille-violaine_1892.xml'>1892</option>";
			<option value='claudel/claudel_jeune-fille-violaine_1899.xml'>1899</option>";
			<option value='claudel/claudel_annonce_1911.xml'>1911</option>";
			<option value='claudel/claudel_annonce_1948.xml'>1948</option>";
		</select>
		<input type="hidden" name="post"/>
	    <input type="submit" value="Valider" />
  </form>
</div>
HTML;

$html .= $form;
if(isset($_POST["post"])){
	$files=$_POST["files"];
	foreach($files as $file){
		$xml = new DOMDocument();
		$xml->load($file);
		$xp = new DOMXpath($xml);
		$xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
		$title = $xp->evaluate("//tei:titleStmt/tei:title")->item(0)->textContent;
		$html.="<h3>".$title."</h3>"
			."<table>"
			."<thead>"
			."<tr>"
			."<th></th>";
		//header

		//acts
		$acts = $xp->evaluate("//tei:div[@type='act']");
		$configurationsCount = 0;
		foreach($acts as $act){
			//prévoir les pièces sans actes
			$colspan = $xp->evaluate("count(descendant::tei:listPerson[@type='configuration'])", $act);
			$configurationsCount += $colspan;
			$html .= ""
				."<th class='act' colspan=".$colspan.">"
				.roman($act->getAttribute("n"))
				."</th>";
		}
		$html .=""
			."</tr>";

		//scenes
		$html2 =""
			."<tr>"
			."<th></th>";
		$scenesCount=0;
		foreach($acts as $act){
			$scenes = $xp->evaluate("descendant::tei:div[@type='scene']", $act);
			$scenesCountAct = $scenes->length;
			$scenesCount += $scenesCountAct;
			if($scenesCountAct==0){
				//acte sans scène
				$colspan = $xp->evaluate("count(descendant::tei:listPerson[@type='configuration'])", $act);
				$html2 .= "<th  class='scene' colspan=".$colspan."></th>";
			}else{
				foreach($scenes as $scene){
					$colspan = $xp->evaluate("count(descendant::tei:listPerson[@type='configuration'])", $scene);
					$html2 .=""
						."<th class='scene' colspan=".$colspan.">"
						.$scene->getAttribute("n")
						."</th>";
				}
			}
		}
		$html2 .= ""
			."</tr>";
		if($scenesCount >0){$html.=$html2;}
		$html.=""
			."</thead>"
			."<tbody>";

		//matcher les config qui n'appartiennent pas à une scène
		//role/config
		// $configurations = $xp->evaluate("//tei:listPerson[@type='configuration']");
		$roles = $xp->evaluate("//tei:person[not(@corresp=preceding::tei:person/@corresp)]");//classé dans l'ordre où ils apparaissent
		// $roles = $xp->evaluate("//tei:role[@xml:id]");//classé dans l'ordre où ils apparaissent
		foreach($roles as $role){
			//  $roleId = $role->getAttribute("xml:id");
			$roleId = substr($role->getAttribute("corresp"), 1);
			$name = $xp->evaluate("//tei:role[@xml:id='".$roleId."']")->item(0)->firstChild->textContent;//corriger en rajoutant des # aux who

			if($name==""){$name=$xp->evaluate("//tei:person[@corresp='#".$roleId."']")->item(0)->getAttribute("n");}
			//evaluate tei:person/@who
			$html .= "<tr>"
				."<td class='role'>"
				.$name
				."</td>";
			$i = 1;
			while($i<=$configurationsCount){
				//   $configurationId = $configuration->getAttribute("xml:id");
				$nextConfiguration = $xp->evaluate("//tei:listPerson[@xml:id][preceding::tei:listPerson[@xml:id='conf".$i."']]");
				//presents
				$present = $xp->evaluate("//tei:listPerson[@xml:id='conf".$i."']//tei:person[@corresp='#".$roleId."']");
				//speakings
				if($i==$configurationsCount){
					$speaking = $xp->evaluate("//tei:listPerson[@xml:id='conf".$i."']/following::tei:sp[@who='".$roleId."']");
				}else{
					$speaking = $xp->evaluate("//tei:listPerson[@xml:id='conf".$i."']/following::tei:sp[following::tei:listPerson[@xml:id='conf".($i+1)."']][@who='".$roleId."']");
				}

				if($speaking->length > 0){
					$html.="<td class='speaking configuration'>1";
				}
				elseif($present->length > 0){
					$html.="<td class='mute configuration'>2";
				}
				else{
					$html.="<td class='absent configuration'>0";
				}
				$html.="</td>";
				$i++;
			}

			$html .="</tr>";
		}
		$html .= ""
			."</tbody>"
			."</table>";
	}
}
$html.="</body>"
	."</html>";
echo $html;
?>