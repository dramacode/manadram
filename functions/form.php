<?php

function form($bdd) {

    
    if (isset($_GET["post"])) {
        echo '<div id="form">
    
    <form method="get" enctype="multipart/form-data">


<div id="str_code">
<textarea name="str_code">';
if(isset($_GET["str_code"])){echo $_GET["str_code"];}
echo '</textarea>
</div>

    <div id="pattern">
        <div class="pattern-wrapper">
            <div class="edit-table">
            <a title="Ajouter une colonne" class="tooltip" id="add_column"><img src="img/addcol.png"/></a>
            <a title="Supprimer une colonne" class="tooltip" id="delete_column"><img src="img/delcol.png"/></a>
            <a title="Ajouter une ligne" class="tooltip" id="add_row"><img src="img/addrow.png"/></a>
            <a title="Supprimer une ligne" class="tooltip" id="delete_row"><img src="img/delrow.png"/></a>
            </div>
           <table class="search pattern">
                <tbody>';
                    foreach($_GET["pattern"] as $key=>$character){
                        echo '<tr class="character" id="'.$key.'">';
                        foreach ($character as $id => $configuration){
                            echo '<td class="configuration"';
                            if($configuration){echo ' style="background:grey"';}
                            echo '><input type="hidden" value="'.$configuration.'" name="pattern['.$key.']['.$id.']"></td>';
                        }
                        echo '</tr>';
                    }
                    
                echo '</tbody>
            </table>

            <div class="submit-wrapper">
                <input type="hidden" name="post">
                <input class="submit" type="submit" value="'.SEARCH.'">
            </div>
            </div>
    </div>

       
     <div id="settings">
        <div class="settings-wrapper">
            <div class="br-settings"></div>
            <div id="options">
                <input class="option-button" type="checkbox" id="group" name="group"';if(isset($_GET["group"])){echo ' checked';}
                echo '>
                <label for="group" class="tooltip" title=\'"AB/A" trouve "ABC/AB" <br/>("A" et "B" représentent un groupe homogène de personnages dont les entrées et les sorties sont solidaires).\'><i class="fa fa-group"></i>'.GROUP.'</label>
                <input class="option-button" type="checkbox" id="ignore_confident" name="ignore_confident"';if(isset($_GET["ignore_confident"])){echo ' checked';}
                echo '>
                <label for="ignore_confident" class="tooltip" title=\'"AB/A" trouve "AB/AC" si C est un confident.\'><i class="fa fa-user-times"></i>'.CONFIDANTS.'</label>
            </div>
            <div id="xpath-configuration">
                <p>'.XPATH.'</p>
                <div id="xpath-fields-configuration">';
                    foreach ($_GET["xpath"] as $id=>$xpath){
                        echo '<input type="text" name="xpath['.$id.']" class="xpath-field-configuration" value="'.$xpath.'"/>';
                    }
                echo '</div>
                <div>
                <a title="Ajouter un champ" class="tooltip" id="add_field_configuration"><i class="fa fa-plus"> </i></a>
                <a title="Supprimer un champ" class="tooltip" id="delete_field_configuration"><i class="fa fa-minus"> </i></a>
                </div>
            </div>          
        </div></div>';
        echo '<div id="filters">';
        $filters = get_filters($bdd);
        include_once("tpl/filters.tpl.php");
        echo '</div>
        
     </form>

</div>';
    }else{
        include("tpl/form.tpl.php");
    }
    
}
?>