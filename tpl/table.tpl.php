
            <h3><?php echo $field["title"];?></h3>
            <div class="table">
                
            <table class="tableFilterTable tableExport" id="<?php echo $key.$needletr;?>">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i><?php echo $field["head"];?></td>
                        <td><i class="fa fa-sort tooltip"></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($table_results[$key] as $table_result){ ?>
                    <tr>
                        <td><?php echo $table_result["title"];?></td>
                        <td><?php echo $table_result["occurrences"];?></td>
                        <td><?php echo $table_result["proportion"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
      
 





 