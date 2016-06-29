<?php
set_time_limit(20000);
error_reporting(E_ALL);
foreach (glob("functions/*.php") as $function) {
    require_once ("functions/" . basename($function));
}
$globs = explode("\n", trim(file_get_contents("corpus.txt")));
$files = array();
foreach ($globs as $glob) {
    $files = array_merge(glob($glob) , $files);
}

//corpus
$corpus = array();
foreach ($files as $file) {
    $corpus[basename($file, ".xml") ] = biblio(basename($file, ".xml"));
}
file_put_contents('data/corpus.php', '<?php $corpus = ' . var_export($corpus, true) . '; ?>');

//haystack
$modes = array(
    "default" => array(
        false,
        false
    ) ,
    "C" => array(
        true,
        false
    ) ,
    "G" => array(
        false,
        true
    ) ,
    "CG" => array(
        true,
        true
    )
);
$i = 1;
while ($i < 11) {
    foreach ($modes as $key => $mode) {
        $haystack = getHaystack($files, $i, $mode[0], $mode[1], $corpus, false);
        file_put_contents("data/haystack" . $i . $key . ".php", '<?php $haystack[' . $i . ']["' . $key . '"] = ' . var_export($haystack, true) . '; ?>');
        
        if ($key == "default") {
            $fields = getFields($haystack, $corpus, $files, $i);
            file_put_contents("data/fields" . $i . ".php", '<?php $fields[' . $i . '] = ' . var_export($fields, true) . '; ?>');
        }
    }
    $i++;
}

//fields
exit;

function getFields($haystack, $corpus, $files, $n) {

    $types = array(
        "author",
        "authorId",
        "title",
        "genre",
        "genreId",
        "play",
        "date",
        "lustrum"
    );
    $fields = array();
    foreach ($haystack as $key => $hay) {
        foreach ($types as $type) {

            //$fields[$type][$hay["id"][$type]]=0;
            
            if (!isset($fields[$type])) {
                $fields[$type] = array();
            }
            $fields[$type][normalize($hay["id"][$type]) ]["field"] = $hay["id"][$type];
            $fields[$type][normalize($hay["id"][$type]) ]["value"] = 0;

            //array_push($fields[$type], array(
            
            //    "field" =>

            
            //    $hay["id"][$type],

            
            //    "value" => 0

            
            //    //$hay["id"][$type]=>0

            
            //));

            
        }
    }

    //dates and lustra : traitement spécifique
    $dates = getDates($files);
    $lustra = array();
    foreach ($dates as $date) {
        $lustrum = roundUpToAny($date);
        $lustrum = ($lustrum - 4) . "-" . $lustrum;
        
        if (!in_array($lustrum, $lustra)) {
            $lustra[] = $lustrum;
        }
    }
    $fields["date"] = array();
    foreach ($dates as $date) {
        $fields["date"][$date] = array(
            "field" => $date,
            "value" => 0
        );
    }
    $fields["lustrum"] = array();
    foreach ($lustra as $lustrum) {
        $fields["lustrum"][$lustrum] = array(
            "field" => $lustrum,
            "value" => 0
        );
    }
    foreach ($haystack as $key => $hay) {
        foreach ($types as $type) {
            foreach ($fields[$type] as $kfield => $field) {
                
                if ($fields[$type][$kfield]["field"] == $hay["id"][$type]) {
                    $fields[$type][$kfield]["value"]++;
                }
            }
        }
    }
    foreach ($types as $type) {
        ksort($fields[$type]);
    }
    return $fields;
}
?>