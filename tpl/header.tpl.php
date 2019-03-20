<div id="header">
    <div class="menu"><a href="#info" id="trigger-info" title="Aide et présentation du projet" class="tooltip-e fancybox"><i class="fa fa-question">&#160;</i></a><a href="#corpus" title="Corpus" id="trigger-corpus" class="tooltip-e fancybox"><i class="fa fa-database">&#160;</i></a><a href="." id="reload" title="Nouvelle recherche" class="tooltip-e fancybox"><i class="fa fa-rotate-left">&#160;</i></a>
    <?php if($lang=="fr") {?>
    <a href="./?lang=en" class="lang tooltip-e" title="English version">en</a><span class="lang"> | fr</span>
    <?php }else{ ?>
    <span class="lang">en | </span><a href="." class="lang tooltip-e" title="Version française">fr</a>
    <?php } ?>
</div>

</div>

<div style="display: none">
    <div id="info" style="width:1200px;height:1200px;overflow:auto;">
        <h2>MAnaDram</h2>
        <p>MAnaDram est un moteur d'analyse dramaturgique qui recherche dans un corpus de pièces de théâtre tous les groupes de scènes présentant la même séquence d'entrée et de sortie des personnages.</p>
	<h3>Recherche à l'aide de la matrice</h3>
        <h4>Exemples</h4>
        <p>Dessiner le motif à rechercher en cliquant sur les cases du tableau. Chaque ligne correspond à un personnage, chaque colonne à une scène.</p>
        <p><img src="img/A-AB-A.png"/></p>
        <p>trouvera toutes les séquences de trois scènes, faisant intervenir deux personnages et deux personnages seulement, dont l'un reste présent en permanence, et dont l'autre n'est présent que durant la scène centrale.</p>
        <h4>Débuts et fins d'acte</h4>
	<p>Une colonne vide représente un entracte ou le début ou la fin de la pièce.</p>
        <p><img src="img/A--A.png"/></p>
	<p>trouvera tous les entractes entourés par deux monologues d'un même personnage.</p>
	<br/>
	<p>Quand le motif dessiné ne commence et ne se finit par une colonne vide, la recherche ne prends pas en compte sa position à l'intérieur de la pièce.</p>
        <p><img src="img/A-AB-B.png"/></p>
        <p>trouvera toutes les séquences de trois scènes où un personnage prononce un monologue et en rencontre un autre qui, dans un troisième temps, reste à son tour seul sur le plateau, que cette séquence se trouve au milieu, au début ou à la fin d'un acte.</p>
	<br/>
	<p>En revanche</p>
        <p><img src="img/A-AB-.png"/></p>
	<p>trouvera toutes les séquences où un personnage monologue puis dialogue avec un autre personnage, et qui se situent à la fin d'un acte.</p>
	<br/>
	<p>Pour élargir la recherche, réduire le nombre de colonnes de la matrices grâce au bouton <img src="img/delcol.png" style="height:15px;display: inline;margin:0;">.</p>
        <p><img src="img/A-AB.png"/></p>
	<p>trouvera toutes les séquences où un personnage monologue puis dialogue avec un autre personnage, quelle que soit sa position dans la pièce. Par conséquent, il est impossible de ne trouver que les occurrences d'un motif donné qui se trouvent au milieu d'un acte, à l'exclusion des occurrences situées au début ou à la fin d'un acte.</p>
        <h4>Grouper les personnages</h4>
        <p><img src="img/A-AB-A-G.png"/></p>
        <p>trouvera toutes les séquences de trois scènes, faisant intervenir deux personnages ou groupes de personnages, dont l'un reste présent en permanence, et dont l'autre n'est présent que durant la scène centrale.</p>
        <p><img src="img/A-G.png"/></p>
        <p>retourne la liste de toutes les scènes du corpus.</p>
        <h4>Ignorer les confidents</h4>
        <p><img src="img/A-AB-C.png"/></p>
        <p>trouvera toutes les séquences de deux scènes où un personnage (et un seul) vient en trouver un autre qui était seul sur le plateau, ainsi que celles où un confident est en outre présent durant l'une et/ou l'autre de ces scènes</p>
	<br/>
	<h3>Recherche par code</h3>
	<p>Il est possible de rechercher un ou plusieurs motifs par son code en cliquant sur le bouton<i class="fa fa-th tooltip-s"></i>. Les différents motifs doivent être séparés par un saut de ligne.</p>
	<ul>
	    <li>Chaque personnage ou groupe de personnage distinct est identifié par une lettre majuscule, selon l'ordre dans lequel ils entrent sur le plateau, puis selon l'ordre inverse dans lequel ils en sortent.</li>
	    <li>Les scènes sont séparées par /.</li>
	    <li>Les personnages ou groupes de personnages, à l'intérieur d'une scène, par -.</li>
	    <li>// indique un entracte.</li>
	</ul>
        <h3>Données</h3>
        <ul>
        <li><a href="http://theatre-classique.fr/">Théâtre classique (Paul Fièvre)</a></li>
        <li><a href="http://bibdramatique.paris-sorbonne.fr/">Bibliothèque dramatique (UMR CELLF 16-21)</a></li>
        <li><a href="http://obvil.paris-sorbonne.fr/corpus/moliere/moliere/">Projet Molière (labex OBVIL)</a></li>
        </ul>

        <br/>
        <p>Programme développé par Marc Douguet dans le cadre du labex OBVIL.</p>
        <p>Contact : marc.douguet[at]gmail.com</p>
        <p><a rel="license" href="http://creativecommons.org/licenses/by-sa/2.0/fr/"><img alt="Licence Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/2.0/fr/88x31.png" /></a></p>
        <div id="test">
        </div>
    </div>
</div>
