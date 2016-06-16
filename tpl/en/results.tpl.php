
<h3>Occurrences</h3>
 <div id="div-results">   
    <table class="tableFilter tableExport" id="table-results">
        <thead>
            <tr>
                <td class="view-table"></td>
                <td class="view-text"></td>
                <td class="view-source"></td>
                <td class="author">Auteur<i class="fa fa-sort"></i></td>
                <td class="title">Titre<i class="fa fa-sort"></i></td>
                <td class="genre">Genre<i class="fa fa-sort"></i></td>
                <td class="date">Année<i class="fa fa-sort"></i></td>
                <td class="act-n">Acte<i class="fa fa-sort"></i></td>
                <td class="scene-n">Scène<i class="fa fa-sort"></i></td>
                <?php foreach ($_POST["xpath"] as $request){ if(!$request){continue;}?>
                <td class="xpath"><?php echo $request; ?><i class="fa fa-sort"></td>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $id=>$result){?>
            <tr>
                <td class="view-table"><a class="tooltip" title="Voir le tableau d'occupation scénique" target="_blank" href="table.php?play=<?php echo $result["play"];?>&scene=<?php echo $result["sceneId"];?>"><i class="fa fa-table"></i></a></td>
                <td class="view-text"><a class="tooltip" title="Voir le texte" target="_blank" href="http://www.theatre-classique.fr/pages/programmes/edition.php?t=../documents/<?php echo strtoupper($result["play"]);?>.xml#A<?php echo $result["act"];?>.S<?php echo $result["act"];?><?php echo $result["scene"];?>"><i class="fa fa-file-text-o"></i></a></td>
                <td class="view-source"><a class="tooltip" title="Voir la source XML" target="_blank" href="http://dramacode.github.io/tcp5/<?php echo $result["play"];?>.xml"><i class="fa fa-file-code-o"></i></a></td>
                <td class="title"><?php echo $result["author"];?></td>
                <td class="title"><i><?php echo $result["title"];?></i></td>
                <td class="genre"><?php echo $result["genre"];?></td>
                <td class="date"><?php echo $result["date"];?></td>
                <td class="act-n"><?php echo roman($result["act"]);?></td>
                <td class="scene-n"><?php echo $result["scene"];?>  <i class="fa fa-eye tooltip-e" title="<?php echo $result["string"];?>"></i></td>
                <?php foreach ($result["xpath"] as $xpath){ ?>
                <td class="xpath"><?php echo $xpath; ?></td>
                <?php }?>
                
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>