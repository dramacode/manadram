<div id="tables">
    <h3>Occurrences</h3>
 <div class="table">   
    <table class="tableFilter tableExport" id="table-results">
        <thead>
            <tr>
                <td class="view-table"></td>
                <td class="view-text"></td>
                <td class="view-source"></td>
                <td class="author"><p><i class="fa fa-sort"></i>Auteur</p></td>
                <td class="title"><p><i class="fa fa-sort"></i>Titre</p></td>
                <td class="genre"><p><i class="fa fa-sort"></i>Genre</p></td>
                <td class="date"><p><i class="fa fa-sort tooltip-e" title="Année de représentation (attestée ou présumée)"></i>Année</p></td>
                <td class="act-n"><p><i class="fa fa-sort"></i>Acte</p></td>
                <td class="scene-n"><p><i class="fa fa-sort tooltip-e" title="Correspond à la première scène du motif"></i>Scène</p></td>
                <?php foreach ($_POST["xpath"] as $request){ if(!$request){continue;}?>
                <td class="xpath"><p><i class="fa fa-sort"></i><?php echo $request; ?></p></td>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $id=>$result){?>
            <tr>
                <td class="view-table"><a class="tooltip" title="Voir le tableau d'occupation scénique" target="_blank" href="table.php?play=<?php echo $result["play"];?>&scene=<?php echo $result["sceneId"];?>"><i class="fa fa-table"></i></a></td>
                <td class="view-text"><a class="tooltip" title="Voir le texte" target="_blank" href="http://www.theatre-classique.fr/pages/programmes/edition.php?t=../documents/<?php echo strtoupper($result["play"]);?>.xml#A<?php echo $result["act"];?>.S<?php echo $result["act"];?><?php echo $result["scene"];?>"><i class="fa fa-file-text-o"></i></a></td>
                <td class="view-source"><a class="tooltip" title="Voir la source XML" target="_blank" href="http://dramacode.github.io/tcp5/<?php echo $result["play"];?>.xml"><i class="fa fa-file-code-o"></i></a></td>
                <td class="author"><p><?php echo $result["author"];?></p></td>
                <td class="title"><p><i><?php echo $result["title"];?></i></p></td>
                <td class="genre"><p><?php echo $result["genre"];?></p></td>
                <td class="date"><p><?php echo $result["date"];?></p></td>
                <td class="act-n"><p><?php echo roman($result["act"]);?></p></td>
                <td class="scene-n"><p><?php echo $result["scene"];?>  <i class="fa fa-eye tooltip-e" title="<?php echo $result["string"];?>"></i></p></td>
                <?php foreach ($result["xpath"] as $xpath){ ?>
                <td class="xpath"><p><?php echo $xpath; ?><p></td>
                <?php }?>
                
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
            <h3>Résultats par auteur</h3>
            <div class="table">
            <table class="tableFilter tableExport" id="author">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Auteur</td>
                        <td><i class="fa fa-sort tooltip" title="Nombre d'occurrences par auteur"></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e" title="Proportion par rapport au nombre total de motifs pour cet auteur dans le corpus"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tables["author"] as $fkey => $fieldResult){ ?>
                    <tr>
                        <td><?php echo $fields["author"][$fkey]["field"];?></td>
                        <td><?php echo $fieldResult["n"];?></td>
                        <td><?php echo $fieldResult["percentage"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
      
                        <h3>Résultats par genre</h3>
            <div class="table">
            <table class="tableFilter tableExport" id="genre">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Genre</td>
                        <td><i class="fa fa-sort tooltip" title="Nombre d'occurrences par genre"></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e" title="Proportion par rapport au nombre total de motifs pour ce genre dans le corpus"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tables["genre"] as $fkey => $fieldResult){ ?>
                    <tr>
                        <td><?php echo $fields["genre"][$fkey]["field"];?></td>
                        <td><?php echo $fieldResult["n"];?></td>
                        <td><?php echo $fieldResult["percentage"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
      
                                    <h3>Résultats par pièce</h3>
<div  class="table">
            <table class="tableFilter tableExport" id="play">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Auteur</td>
                        <td><i class="fa fa-sort"></i>Titre</td>
                        <td><i class="fa fa-sort tooltip" title="Nombre d'occurrences par pièce""></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e" title="Proportion par rapport au nombre total de motifs pour cette pièce"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tables["play"] as $fkey => $fieldResult){ ?>
                    <tr>
                        <td><?php echo $corpus[$fkey]["author"];?></td>
                        <td><?php echo $corpus[$fkey]["title"];?></td>
                        <td><?php echo $fieldResult["n"];?></td>
                        <td><?php echo $fieldResult["percentage"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>

   
                                    <h3>Résultats par année</h3>
<div  class="table">
            <table class="tableFilter tableExport" id="lustrum">
                <thead>
                    <tr>
                        <td><i class="fa fa-sort"></i>Année</td>
                        <td><i class="fa fa-sort tooltip" title="Nombre d'occurrences par période"></i>Occurrences</td>
                        <td><i class="fa fa-sort tooltip-e" title="Proportion par rapport au nombre total de motifs pour cette période dans le corpus"></i>Proportion (%)</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tables["lustrum"] as $fkey => $fieldResult){ ?>
                    <tr>
                        <td><?php echo $fields["lustrum"][$fkey]["field"]; ?></td>
                        <td><?php echo $fieldResult["n"];?></td>
                        <td><?php echo $fieldResult["percentage"];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
     

<?php foreach($tables["xpath"] as $key=>$xpath){?>
    
        <h3><?php echo $_POST["xpath"]["xpath-".$key];?></h3>
        <div class="table">
        <table class="tableFilter tableExport" id="xpath-<?php echo $key; ?>">
            <thead>
                <tr>
                    <td><i class="fa fa-sort tooltip" title="Résultats de la requête"></i><?php echo $_POST["xpath"]["xpath-".$key];?></td>
                    <td><i class="fa fa-sort tooltip" title="Nombre d'occurrences pour ce résultat"></i>Occurrences</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($xpath as $value => $fieldResult){ ?>
                <tr>
                    <td><?php echo $value; ?></td>
                    <td><?php echo $fieldResult["n"];?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table></div>
    
    <?php } ?>

</div>






 