<?php

set_time_limit(200000);
ini_set('memory_limit', '1024M');

//error_reporting(E_ALL);


//error_reporting(0);

foreach (glob("functions/*.php") as $function) {
    include_once ("functions/" . basename($function));
}
$bdd = connect();

//

////vider les tables

$sql = "DELETE FROM 'pattern'";
insert($sql, $bdd);
$sql = "DELETE FROM 'play'";
insert($sql, $bdd);
$sql = "DELETE FROM 'stats'";
insert($sql, $bdd);
$sql = "DELETE FROM 'configuration'";
insert($sql, $bdd);
$sql = "DELETE FROM 'role'";
insert($sql, $bdd);
//$bdd = NULL;
//return;
////

//get corpus

$globs = explode("\n", trim(file_get_contents("corpus.txt")));
$files = array();
foreach ($globs as $glob) {
    $files = array_merge(glob($glob) , $files);
}

//play and characters

//foreach ($files as $file) {


//    $play = basename($file, ".xml");


//    $corpus = biblio($play);


//}


//print_r


//characters


//patterns

$modes = array(
    "default" => array(
	0,
	0
    ) ,
    "C" => array(
	1,
	0
    ) ,
    "G" => array(
	0,
	1
    ) ,
    "CG" => array(
	1,
	1
    )
);
$dom = new DOMDocument();
$j = 0;
foreach ($files as $file) {
    
    $play_code = basename($file, ".xml");
    echo $play_code;
    $biblio = biblio($play_code);
    $html = get_table($play_code);
    //echo $play_code;
    //continue;
    $sql = "INSERT INTO play (
    code,
    author,
    title,
    genre,
    created,
    lustrum,
    html
    ) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?
    )";
    $data = array(
	$play_code,
	$biblio["author"],
	$biblio["title"],
	$biblio["genre"],
	(int)$biblio["date"],
	$biblio["lustrum"],
	$html
    );
    insert($sql, $bdd, $data);
    $sql = "SELECT LAST_INSERT_ROWID()";
    $play_id = select($sql, $bdd);
    $play_id = reset($play_id);
    $dom->load($file);
    $xp = new DOMXPath($dom);
    $xp->registerNamespace("tei", "http://www.tei-c.org/ns/1.0");
    $i = 1; //longueur min des motifs à extraire. Les motifs A//B, A/, /A sont extraits en même temps que (respectivement) les motifs de 3 (A/AB/A) et 2 (A/B) conf, mais ils sont insérés en base avec l = 2 et l=1

    while ($i <= 5) { //longueur max des motifs à extraire

	foreach ($modes as $kmode => $mode) {
	    $patterns = extract_patterns($xp, $i, $mode[0], $mode[1]);
	    $occurrences = occurrences($patterns);
	    $stats = 0;
	    $sql = "INSERT INTO stats (
	    play_id,
	    value,
	    l,
	    c,
	    g
	    ) VALUES (
	    ?,
	    ?,
	    ?,
	    ?,
	    ?            
	    )";
	    $data = array(
		$play_id,
		0,
		$i,
		$mode[0],
		$mode[1],
	    );
	    insert($sql, $bdd, $data);
	    foreach ($patterns as $code => $pattern) { //le faire à l'intérieur d'extract pour éviter de reboucler, mais où insérer les stats ?

		$list_id = implode("+", $occurrences[$pattern["int_bin"]]);
		$sql = "INSERT INTO pattern (
		play_id,
		code,
		act_n,
		scene_n,
		scene_id,
		conf_id,
		occurrences,
		int_dec,
		int_bin,
		str_code,
		str_id,
		str_name,
		l,
		c,
		g
		) VALUES (
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?,
		?
		)";
		$data = array(
		    (int)$play_id, //récupérer id numérique

		    $code,
		    (int)$pattern["act_n"],
		    (int)$pattern["scene_n"],
		    $pattern["scene_id"],
		    $pattern["conf_id"],
		    $list_id,
		    $pattern["int_dec"], //transforme en float pour les nombres trop élevés : pb pour sqlite ?

		    $pattern["int_bin"],
		    $pattern["str_code"],
		    $pattern["str_id"],
		    $pattern["str_name"],
		    $pattern["l"], //je prends le nombre de conf, et non la longueur de la matrice (la conf vide qui marque l'entracte est un code, A//B est un motif de l = 2)

		    $mode[0],
		    $mode[1]
		);
		insert($sql, $bdd, $data);
		$sql = "UPDATE stats SET value = value +1 WHERE play_id = " . (int)$play_id . " AND l = " . $pattern["l"] . " AND c = " . $mode[0] . " AND g = " . $mode[1];
		insert($sql, $bdd);

		//quand je suis à l = 1 et que j'extrait un motif à entracte ?
		
		//ds le form, si j'entre un entracte, spécifier aussi la longueur

		
	    }
	}
	$i++;
    }
}
$filters = get_filters($bdd);
ob_start();
include ("tpl/filters.tpl.php");
$filters = ob_get_clean();
file_put_contents("html/filters.html", $filters);

$corpus = get_corpus($bdd);
ob_start();
include ("tpl/table_corpus.tpl.php");
$list = ob_get_clean();
file_put_contents("html/corpus.html", $list);
$bdd = NULL;
?>