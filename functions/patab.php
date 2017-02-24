<?php

function patab() {
    //PATAB
    $patab = '<table class="pattern">';
    foreach ($_GET["pattern"] as $character) {
        $patab.= '<tr>';
        foreach ($character as $configuration) {
            $patab.= ($configuration == 1) ? '<td class="configuration speaking"></td>' : '<td class="configuration absent"></td>';
        }
        $patab.= '</tr>';
    }
    $patab.= '</table>';
    return $patab;
}
?>