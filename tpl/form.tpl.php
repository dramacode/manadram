<div id="form">
    <form method="get" enctype="multipart/form-data">
         <div id="filters" class="form-block form-filters">
            <h4>Filtres</h4>
            <?php if(isset($_GET["post"])){
                $filters = get_filters($bdd);
                include_once("tpl/filters.tpl.php");
            } else {include_once("html/filters.html");} ?>
        </div>        
        <div id="pattern" class="form-block form-pattern">
            <div class="pattern-wrapper" <?php if(isset($_GET["str_code"])){if($_GET["str_code"]){echo "style='display:block;'";} else {echo "style='display:none;'";}}else{echo "style='display:none;'";} ?>>
                <div class="pattern-title"><h4>Motif <a class="toggle-pattern"><i class="fa fa-th tooltip-s" title="Basculer l'affichage"></i></a></h4></div>                    
                <textarea class="str_code" name="str_code" rows="10" cols="16"><?php if(isset($_GET["str_code"])){if($_GET["str_code"]){echo $_GET["str_code"];}} ?></textarea>
            </div>
            <div class="pattern-wrapper" <?php if(isset($_GET["str_code"])){if($_GET["str_code"]){echo "style='display:none;'";}} ?>>
                <div class="pattern-title"><h4>Motif <a class="toggle-pattern"><i class="fa fa-list tooltip-s" title="Basculer l'affichage"></i></a></h4></div>                    
                <div class="edit-table">
                    <a title="Ajouter une colonne" class="tooltip" id="add_column"><img src="img/addcol.png"/></a>
                    <a title="Supprimer une colonne" class="tooltip" id="delete_column"><img src="img/delcol.png"/></a>
                    <a title="Ajouter une ligne" class="tooltip" id="add_row"><img src="img/addrow.png"/></a>
                    <a title="Supprimer une ligne" class="tooltip" id="delete_row"><img src="img/delrow.png"/></a>
                </div>
               <table class="search pattern">
                    <tbody>
                    <?php if (isset($_GET["pattern"])){foreach($_GET["pattern"] as $key=>$character){ ?>
                        <tr class="character" id="<?php echo $key; ?>">
                        <?php foreach ($character as $id => $configuration){ ?>
                            <td class="configuration" <?php if($configuration){echo ' style="background:grey"';} ?>><input type="hidden" value="<?php echo $configuration;?>" name="pattern[<?php echo $key;?>][<?php echo $id;?>]"></td>
                        <?php } ?>
                        </tr>
                    <?php }} else { ?>
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
                    <?php } ?>
                    </tbody>
                </table>
                   
            </div>
            <input type="hidden" name="post">
            <input class="submit" type="submit" value="<?php echo SEARCH;?>">           
        </div>    
        <div id="settings" class="form-block form-settings">
            <h4>Options</h4>
            <div class="settings-wrapper">
                <div class="br-settings"></div>
                <div id="options">
                    <input class="option-button" type="checkbox" id="group" name="group" <?php if(isset($_GET["group"])){echo ' checked';}?>>
                    <label for="group" class="tooltip" title='"AB/A" trouve "ABC/AB" <br/>("A" et "B" représentent un groupe homogène de personnages dont les entrées et les sorties sont solidaires).'><i class="fa fa-group"></i><?php echo GROUP;?></label>
                    <input class="option-button" type="checkbox" id="ignore_confident" name="ignore_confident" <?php if(isset($_GET["ignore_confident"])){echo ' checked';}?>>
                    <label for="ignore_confident" class="tooltip" title='"AB/A" trouve "AB/AC" si C est un confident.'><i class="fa fa-user-times"></i><?php echo CONFIDANTS;?></label>
                </div>
                <div id="xpath-configuration">
                    <p><?php echo XPATH;?></p>
                    <div id="xpath-fields-configuration">
                    <?php if(isset($_GET["xpath"])){foreach ($_GET["xpath"] as $id=>$xpath){ ?>
                        <input type="text" name="xpath[<?php echo $id;?>]" class="xpath-field-configuration" value="<?php echo $xpath;?>"/>
                    <?php }} else { ?>
                        <input type="text" name="xpath[xpath-0]" class="xpath-field-configuration">
                    <?php } ?>
                    </div>
                    <div>
                    <a title="Ajouter un champ" class="tooltip" id="add_field_configuration"><i class="fa fa-plus"> </i></a>
                    <a title="Supprimer un champ" class="tooltip" id="delete_field_configuration"><i class="fa fa-minus"> </i></a>
                    </div>
                </div>          
            </div>
        </div>
    </form>
</div>
