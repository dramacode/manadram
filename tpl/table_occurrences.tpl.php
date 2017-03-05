 <h3>Occurrences</h3>
 <div class="table">
    <table class="tableFilterOccurrences tableExport" id="table-results<?php echo $needletr;?>">
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
                <?php foreach ($_GET["xpath"] as $request){ if(!$request){continue;}?>
                <td class="xpath"><p><i class="fa fa-sort"></i><?php echo $request; ?></p></td>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $result){?>
            <tr>
                <td class="view-table"><a title="Voir le tableau d'occupation scénique" target="_blank" href="table.php?play=<?php echo $result["code"];?>&scene=<?php echo $result["occurrences"];?>"><i class="fa fa-table"></i></a></td>
                <td class="view-text"><a class="tooltip" title="Voir le texte" target="_blank" href="http://www.theatre-classique.fr/pages/programmes/edition.php?t=../documents/<?php echo strtoupper($result["code"]);?>.xml#A<?php echo $result["act_n"];?>.S<?php echo $result["act_n"];?><?php echo $result["scene_n"];?>"><i class="fa fa-file-text-o"></i></a></td>
                <td class="view-source"><a class="tooltip" title="Voir la source XML" target="_blank" href="http://dramacode.github.io/tcp5/<?php echo $result["code"];?>.xml"><i class="fa fa-file-code-o"></i></a></td>
                <td class="author"><p><?php echo $result["author"];?></p></td>
                <td class="title"><p><i><?php echo $result["title"];?></i></p></td>
                <td class="genre"><p><?php echo $result["genre"];?></p></td>
                <td class="date"><p><?php echo $result["created"];?></p></td>
                <td class="act-n"><p><?php echo roman($result["act_n"]);?></p></td>
                <td class="scene-n"><p><?php echo $result["scene_n"];?>  <i class="fa fa-eye tooltip-e" title="<?php echo $result["str_name"];?>"></i></p></td>
                <?php if(isset($result["xpath"])){
                    foreach ($result["xpath"] as $xpath){ ?>
                <td class="xpath"><p><?php echo $xpath; ?><p></td>
                <?php }}?>
                
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
         




 