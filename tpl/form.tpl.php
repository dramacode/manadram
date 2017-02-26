<div id="form">
    
    <form method="get" enctype="multipart/form-data">


    
<div id="str_code">
<textarea name="str_code"></textarea>
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
                <tbody>
                    
                    <tr class="character" id="A">
                        <td class="configuration"><input type="hidden" value="0" name="pattern[A][0]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[A][1]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[A][2]"></td>
                    </tr>

                    <tr class="character" id="B">
                        <td class="configuration"><input type="hidden" value="0" name="pattern[B][0]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[B][1]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[B][2]"></td>
                    </tr>
                    <tr class="character" id="C">
                        <td class="configuration"><input type="hidden" value="0" name="pattern[C][0]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[C][1]"></td>
                        <td class="configuration"><input type="hidden" value="0" name="pattern[C][2]"></td>
                    </tr>
                </tbody>
            </table>

            <div class="submit-wrapper">
                <input type="hidden" name="post">
                <input class="submit" type="submit" value="<?php echo SEARCH;?>">
            </div>
            </div>
    </div>

       
     <div id="settings">
        <div class="settings-wrapper">
            <div class="br-settings"></div>
            <div id="options">
                <input class="option-button" type="checkbox" id="group" name="group">
                <label for="group" class="tooltip" title='"AB/A" trouve "ABC/AB" <br/>("A" et "B" représentent un groupe homogène de personnages dont les entrées et les sorties sont solidaires).'><i class="fa fa-group"></i><?php echo GROUP;?></label>
                <input class="option-button" type="checkbox" id="ignore_confident" name="ignore_confident">
                <label for="ignore_confident" class="tooltip" title='"AB/A" trouve "AB/AC" si C est un confident.'><i class="fa fa-user-times"></i><?php echo CONFIDANTS;?></label>
            </div>
            <div id="xpath-configuration">
                <p><?php echo XPATH;?></p>
                <div id="xpath-fields-configuration">
                    <input type="text" name="xpath[xpath-0]" class="xpath-field-configuration">
                </div>
                <div>
                <a title="Ajouter un champ" class="tooltip" id="add_field_configuration"><i class="fa fa-plus"> </i></a>
                <a title="Supprimer un champ" class="tooltip" id="delete_field_configuration"><i class="fa fa-minus"> </i></a>
                </div>
            </div>          
        </div></div>
     <div id="filters">
     <?php
        echo file_get_contents("tpl/filters.html");
        ?>
        </div>
     </form>

</div>
