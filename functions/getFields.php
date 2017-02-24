<?php

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

        //$lustrum = roundUpToAny($date);
        
        //$lustrum = ($lustrum - 5) . "-" . ($lustrum-1);

        
        //

        $lustrum = lustrum($date);
        
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