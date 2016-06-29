<div id="header">
    <a href="http://obvil.paris-sorbonne.fr" class="logo"><img src="http://obvil.paris-sorbonne.fr/sites/default/files/logo.png"></a>

    <div class="menu"><a href="#info" id="trigger-info" title="Aide et présentation du projet" class="tooltip-e fancybox"><i class="fa fa-question">&#160;</i></a><a href="#corpus" title="Corpus" id="trigger-corpus" class="tooltip-e fancybox"><i class="fa fa-database">&#160;</i></a><a href="." id="reload" title="Nouvelle recherche" class="tooltip-e fancybox"><i class="fa fa-rotate-left">&#160;</i></a></div>
</div>

<div style="display: none">
    <div id="info" style="width:1200px;height:1200px;overflow:auto;">
        <h2>MAnaDram</h2>
        <p>MAnaDram est un moteur d'analyse dramaturgique qui recherche dans un corpus de pièces de théâtre tous les groupes de scènes présentant la même séquence d'entrée et de sortie des personnages.</p>
        <h4>Exemples</h4>
        <p>Dessiner le motif à rechercher en cliquant sur les cases du tableau. Chaque ligne correspond à un personnage, chaque colonne à une scène.</p>
        <p><img src="img/A-AB-A.png"/></p>
        <p>trouvera toutes les séquences de trois scènes, faisant intervenir deux personnages et deux personnages seulement, dont l'un reste présent en permanence, et dont l'autre n'est présent que durant la scène centrale.</p>
        <p><img src="img/A.png"/></p>
        <p>trouvera tous les monologues.</p>
        <h4>Grouper les personnages</h4>
        <p><img src="img/A-AB-A-G.png"/></p>
        <p>trouvera toutes les séquences de trois scènes, faisant intervenir deux personnages ou groupes de personnages, dont l'un reste présent en permanence, et dont l'autre n'est présent que durant la scène centrale.</p>
        <p><img src="img/A-G.png"/></p>
        <p>retourne la liste de toutes les scènes du corpus.</p>
        <h4>Ignorer les confidents</h4>
        <p><img src="img/A-AB-C.png"/></p>
        <p>trouvera toutes les séquences de deux scènes où un personnage (et un seul) vient en trouver un autre qui était seul sur le plateau, ainsi que celles où un confident est en outre présent durant l'une et/ou l'autre de ces scènes</p>
        <h4>Requête XPath</h4>
        <p>Les requêtes sont évaluées à partir de la première scène de chaque séquence correspondant au motif recherché. Le résultat affiché correspond au nombre d'éléments renvoyés par la requête</p>
        <p>La requête doit respecter la syntaxe XPath 1.0. Les éléments doivent être précédés de l'espace de nom "tei"</p>
        <ul>
            <li>./parent::* récupère l'acte courant </li>
            <li>./tei:sp renvoie le nombre de répliques de la scène</li>
        </ul>
        <br/>
        <h4>Données</h4>
        <ul>
        <li><a href="http://theatre-classique.fr/">Théâtre classique (Paul Fièvre)</a></li>
        <li><a href="http://bibdramatique.paris-sorbonne.fr/">Bibliothèque dramatique (UMR CELLF 16-21)</a></li>
        <li><a href="http://obvil.paris-sorbonne.fr/corpus/moliere/moliere/">Projet Molière (labex OBVIL)</a></li>
        </ul>
        
        <br/>
        <p>Programme développé par Marc Douguet dans le cadre du labex OBVIL.</p>
        <p>Contact : marc.douguet[at]gmail.com</p>
        <div id="test">
        </div>
    </div>
</div>
