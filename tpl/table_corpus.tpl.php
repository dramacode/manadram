       <div style="display: none">
            <div id="corpus" style="width:1200px;height:1200px;overflow:auto;">
                 


    <table class="tableExport tableFilterCorpus" id="table-corpus">
        <thead>
            <tr>
                <td class="view-table"></td>
                <td class="view-text"></td>
                <td class="view-source"></td>
                <td class="author"><i class="fa fa-sort"></i>Auteur</td>
                <td class="title"><i class="fa fa-sort"></i>Titre</td>
                <td class="genre"><i class="fa fa-sort"></i>Genre</td>
                <td class="date"><i class="fa fa-sort tooltip-e" title="Année de représentation (attestée ou présumée)"></i>Année</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($corpus as $id=>$book){?>
            <tr>
                <td class="view-table"><a class="tooltip" title="Voir le tableau d'occupation scénique" target="_blank" href="table.php?play=<?php echo $book["code"];?>"><i class="fa fa-table"></i></a></td>
                <td class="view-text"><a class="tooltip" title="Voir le texte" target="_blank" href="http://www.theatre-classique.fr/pages/programmes/edition.php?t=../documents/<?php echo strtoupper($book["code"]);?>.xml"><i class="fa fa-file-text-o"></i></a></td>
                <td class="view-source"><a class="tooltip" title="Voir la source XML" target="_blank" href="../tcp5/<?php echo $book["code"];?>.xml"><i class="fa fa-file-code-o"></i></a></td>
                <td class="title"><?php echo $book["author"];?></td>
                <td class="title"><i><?php echo $book["title"];?></i></td>
                <td class="genre"><?php echo $book["genre"];?></td>
                <td class="date"><?php echo $book["created"];?></td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
                        </div>
