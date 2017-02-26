
            <div class="table">
    <a class="tooltip export-link" title="Télécharger les résultats au format CSV" id="export-table-summary"><i class="fa fa-download"></i></a>
                
            <table class="tableFilterCode tableExport" id="table-summary">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Motif</td>
                        <td><i class="fa fa-sort"></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
            <?php
    foreach($all_results as $needle => $all_result){

?>                    <tr>
                        <td><?php echo $needle;?></td>
                        <td><?php echo $all_result["count"];?></td>
                        <td><?php echo $all_result["proportion"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
