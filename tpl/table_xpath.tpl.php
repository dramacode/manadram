
            <h3><?php echo $field["title"];?></h3>
            <div class="table">
            <table class="tableFilterXPath tableExport" id="<?php echo $key;?>">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Valeur</td>
                        <td><i class="fa fa-sort"></i>Occurrences</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table_results[$key] as $value=>$table_result){ ?>
                    <tr>
                        <td><?php echo $value;?></td>
                        <td><?php echo $table_result;?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
      
 





 