<?xml version="1.0" encoding="UTF-8"?>
<!--
  
<h1>TEI » HTML (tei2html.xsl)</h1>

LGPL  http://www.gnu.org/licenses/lgpl.html
© 2005 ajlsm.com (Cybertheses)
© 2007 Frederic.Glorieux@fictif.org
© 2010 Frederic.Glorieux@fictif.org et École nationale des chartes
© 2012 Frederic.Glorieux@fictif.org 
© 2013 Frederic.Glorieux@fictif.org et LABEX OBVIL

<p>
Cette transformation XSLT 1.0 (compatible navigateurs, PHP, Python, Java…) 
transforme du TEI en HTML5.
Les auteurs ne s'engagent pas à supporter les 600 éléments TEI.
Cette feuille prend en charge <a href="http://www.tei-c.org/Guidelines/Customization/Lite/">TEI lite</a>
et les éléments TEI documentés dans les <a href="./../schema/">schémas</a> de cette installation.
</p>
<p>
Alternative : les transformations de Sebastian Rahtz <a href="http://www.tei-c.org/Tools/Stylesheets/">tei-c.org/Tools/Stylesheets/</a>
sont officiellement ditribuées par le consortium TEI, cependant ce développement est en XSLT 2.0 (java requis).
</p>
-->
<xsl:transform version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:rng="http://relaxng.org/ns/structure/1.0" xmlns:eg="http://www.tei-c.org/ns/Examples" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:html="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" exclude-result-prefixes="eg html rng tei epub" xmlns:exslt="http://exslt.org/common" extension-element-prefixes="exslt">
  <xsl:import href="common.xsl"/>
  <!-- Name of this xsl  -->
  <xsl:param name="this">tei2toc.xsl</xsl:param>
  <!-- No XML declaration for html fragments -->
  <xsl:output encoding="UTF-8" indent="yes" method="xml" omit-xml-declaration="yes"/>
  <!-- What kind of root element to output ? html, nav, only front toc, or back or body -->
  <xsl:param name="root" select="$html"/>
  <!-- Racine -->
  <xsl:template match="/*">
    <xsl:choose>
      <xsl:when test="$root=$front">
        <xsl:call-template name="toc-front"/>
      </xsl:when>      
      <xsl:when test="$root=$back">
        <xsl:call-template name="toc-back"/>
      </xsl:when>      
      <!-- HTML fragment -->
      <xsl:when test="$root=$nav">
        <nav>
          <xsl:call-template name="att-lang"/>
          <xsl:call-template name="toc-header"/>
          <xsl:call-template name="toc"/>
        </nav>
      </xsl:when>
      <!-- Complete doc -->
      <xsl:otherwise>
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html></xsl:text>
        <xsl:value-of select="$lf"/>
        <html>
          <xsl:call-template name="att-lang"/>
          <head>
            <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
            <meta name="modified" content="{$date}"/>
            <!-- déclaration classes css locale (permettre la surcharge si généralisation) -->
            <!-- à travailler
            <xsl:apply-templates select="/*/tei:teiHeader/tei:encodingDesc/tei:tagsDecl"/>
            -->
            <link rel="stylesheet" type="text/css" href="{$theme}tei2html.css"/>
            <script type="text/javascript" src="{$theme}Tree.js">//</script>
          </head>
          <body class="{$corpusid}">
            <nav>
              <xsl:call-template name="toc-header"/>
              <xsl:call-template name="toc"/>
            </nav>
          </body>
        </html>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template name="toc-header">
    <header>
      <a>
        <xsl:attribute name="href">
          <xsl:for-each select="/*/tei:text">
            <xsl:call-template name="href"/>
          </xsl:for-each>
        </xsl:attribute>
        <xsl:if test="$byline">
          <xsl:copy-of select="$byline"/>
          <xsl:text> </xsl:text>
        </xsl:if>
        <xsl:if test="$docdate != ''">
          <span class="docDate">
            <xsl:text> (</xsl:text>
            <xsl:value-of select="$docdate"/>
            <xsl:text>)</xsl:text>
          </span>
        </xsl:if>
        <br/>
        <xsl:copy-of select="$doctitle"/>
      </a>
    </header>
  </xsl:template>

  <!--
<h3>mode="a" (lien html)</h3>

<p>
Générer un lien html &lt;a href="href" title="title">label&lt;/a> pour un élément, notamment pour une table des matières,
mais aussi pour le liage dans l'apparat critique. Ce mode fait usage des modes :
</p>
<ul>
  <li>“label” : titre court pour l'intitulé du lien</li>
  <li>“title” : titre long pour une bulle surgissante</li>
  <li>“href” : lien URI relatif fonctionnant dans le contexte de la publication (en un ou plusieurs fichiers)</li>
</ul>
  -->
  <xsl:template name="a">
    <xsl:apply-templates select="." mode="a"/>
  </xsl:template>
  <!-- Par défaut, le mode lien ne produit rien et alerte d'un manque dans la sortie. -->
  <xsl:template match="node()" mode="a">
    <b style="color:red;">&lt;<xsl:value-of select="name()"/> mode="a"&gt;</b>
  </xsl:template>
  <xsl:template match="tei:pb" mode="a">
    <a>
      <xsl:attribute name="href">
        <xsl:call-template name="href"/>
      </xsl:attribute>
      <xsl:variable name="n" select="normalize-space(translate(@n, '()[]{}', ''))"/>
      <xsl:choose>
        <xsl:when test="$n != ''  and contains('0123456789IVXDCM', substring(@n,1,1))">p. <xsl:value-of select="$n"/></xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="$n"/>
        </xsl:otherwise>
      </xsl:choose>
    </a>
  </xsl:template>
  <!--
  <xsl:template match="tei:castList" mode="a">
    <xsl:choose>
      <xsl:when test="tei:head">
        <xsl:apply-templates select="tei:head"/>
      </xsl:when>
      <xsl:otherwise>
        <xsl:call-template name="message"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  -->
  <!-- section, liées sur leur titres  -->
  <xsl:template match="tei:body | tei:back | tei:castList | tei:div | tei:div0 | tei:div1 | tei:div2 | tei:div3 | tei:div4 | tei:div5 | tei:div6 | tei:div7 | tei:front | tei:group | tei:text" mode="a">
    <xsl:param name="class"/>
    <a>
      <xsl:attribute name="href">
        <xsl:call-template name="href"/>
      </xsl:attribute>
      <xsl:if test="$class">
        <xsl:attribute name="class">
          <xsl:value-of select="$class"/>
        </xsl:attribute>
      </xsl:if>
      <!-- titre long -->
      <xsl:variable name="title">
        <xsl:call-template name="title"/>
      </xsl:variable>
      <!-- Spec titre court ? <index> ? -->
      <xsl:copy-of select="$title"/>
      <!-- compte d'items -->
      <xsl:if test="self::tei:group">
        <xsl:text> </xsl:text>
        <small>
          <xsl:text>(</xsl:text>
          <xsl:value-of select="count(.//tei:text)"/>
          <xsl:text>)</xsl:text>
        </small>
      </xsl:if>
    </a>
  </xsl:template>
  <!-- affichage de noms avec initiales (par ex resp) -->
  <xsl:template match="tei:principal" mode="a">
    <a class="resp">
      <xsl:attribute name="href">
        <xsl:call-template name="href"/>
      </xsl:attribute>
      <xsl:attribute name="title">
        <xsl:value-of select="normalize-space(.)"/>
      </xsl:attribute>
      <xsl:value-of select="@xml:id | @id"/>
    </a>
  </xsl:template>
  <!-- Liens courts vers un nom -->
  <xsl:template match="tei:name[@xml:id]" mode="a">
    <a href="#{@xml:id}">
      <xsl:variable name="text">
        <xsl:apply-templates select="text()"/>
      </xsl:variable>
      <xsl:choose>
        <xsl:when test="tei:addName|tei:forename|tei:surname">
          <xsl:apply-templates select="tei:addName|tei:forename|tei:surname"/>
        </xsl:when>
        <xsl:when test="normalize-space($text) != ''">
          <xsl:value-of select="normalize-space($text)"/>
        </xsl:when>
        <xsl:otherwise>
          <xsl:apply-templates/>
        </xsl:otherwise>
      </xsl:choose>
    </a>
  </xsl:template>


  <!--
<h3>mode="li" toc</h3>
-->
  <!-- default, stop -->
  <xsl:template match="node()" mode="li"/>
  <xsl:template match="tei:castList" mode="li">
    <li>
      <xsl:call-template name="a"/>
    </li>
  </xsl:template>
  <!-- sectionnement, traverser -->
  <xsl:template match="tei:back | tei:body | tei:div | tei:div0 | tei:div1 | tei:div2 | tei:div3 | tei:div4 | tei:div5 | tei:div6 | tei:div7 | tei:front | tei:group " mode="li">
    <xsl:param name="class">tree</xsl:param>
    <!-- un truc pour pouvoir maintenir ouvert des niveaux de table des matières -->
    <xsl:param name="less" select="0"/>
    <!-- limit depth -->
    <xsl:param name="depth"/>
    <li>
      <xsl:choose>
        <!-- last level -->
        <xsl:when test="count(tei:back | tei:body | tei:div | tei:div0 | tei:div1 | tei:div2 | tei:div3 | tei:div4 | tei:div5 | tei:div6 | tei:div7 | tei:front | tei:group | tei:text ) &lt; 1"/>
        <!-- let open -->
        <xsl:when test="number($depth) &lt; 2"/>
        <xsl:when test="number($less) &gt; 0">
          <xsl:attribute name="class">less</xsl:attribute>
        </xsl:when>
        <xsl:otherwise>
          <xsl:attribute name="class">more</xsl:attribute>
        </xsl:otherwise>
      </xsl:choose>
      <xsl:call-template name="a"/>
      <xsl:choose>
        <!-- depth found, stop -->
        <xsl:when test="$depth = 0"/>
        <xsl:when test="count(tei:group | tei:text | tei:div 
        | tei:div0 | tei:div1 | tei:div2 | tei:div3 | tei:div4 | tei:div5 | tei:div6 | tei:div7 ) &gt; 0">
          <ol>
            <xsl:if test="$class">
              <xsl:attribute name="class">
                <xsl:value-of select="$class"/>
              </xsl:attribute>
            </xsl:if>
            <xsl:for-each select="tei:back | tei:body | tei:castList | tei:div | tei:div0 | tei:div1 | tei:div2 | tei:div3 | tei:div4 | tei:div5 | tei:div6 | tei:div7 | tei:front | tei:group | tei:text">
              <xsl:choose>
                <!-- ??? first section with no title, no forged title -->
                <xsl:when test="self::div and position() = 1 and not(tei:head) and ../tei:head "/>
                <xsl:otherwise>
                  <xsl:apply-templates select="." mode="li">
                    <xsl:with-param name="less" select="number($less) - 1"/>
                    <xsl:with-param name="depth" select="number($depth) - 1"/>
                    <xsl:with-param name="class"/>
                  </xsl:apply-templates>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
          </ol>
        </xsl:when>
      </xsl:choose>
    </li>
    <!-- ??
          <xsl:when test="(/*/tei:text/tei:front and count(.|/*/tei:text/tei:front) = 1) 
            or (/*/tei:text/tei:back and count(.|/*/tei:text/tei:back) = 1)">
            <xsl:call-template name="a"/>
          </xsl:when>
      -->
  </xsl:template>
  <!-- Générer une navigation dans les sections -->
  <xsl:template name="toc">
    <xsl:param name="less"/>
    <xsl:param name="depth">
      <xsl:choose>
        <xsl:when test="key('id', 'toc-depth')">
          <xsl:value-of select="key('id', 'toc-depth')/@n"/>
        </xsl:when>
      </xsl:choose>
    </xsl:param>
    <ol class="tree">
      <!-- front in one <li> to hide some by default  -->
      <xsl:choose>
        <xsl:when test="/*/tei:text/tei:front[count(tei:div|tei:div1) = 1]">
          <xsl:apply-templates select="/*/tei:text/tei:front/*" mode="li">
            <xsl:with-param name="depth" select="$depth"/>
            <xsl:with-param name="less" select="$less"/>
          </xsl:apply-templates>
        </xsl:when>
        <xsl:when test="/*/tei:text/tei:front[tei:div[normalize-space(.) != '']|tei:div1[normalize-space(.) != '']]">
          <li class="more">
            <span>
              <xsl:apply-templates select="/*/tei:text/tei:front" mode="title"/>
            </span>
            <ol>
              <xsl:apply-templates select="/*/tei:text/tei:front/*[self::tei:div|self::tei:div1][normalize-space(.) != '']" mode="li">
                <xsl:with-param name="depth" select="$depth"/>
                <xsl:with-param name="less" select="$less"/>
              </xsl:apply-templates>
            </ol>
          </li>
        </xsl:when>
      </xsl:choose>
      <xsl:apply-templates select="/*/tei:text/tei:body/* | /*/tei:text/tei:group/* " mode="li">
        <xsl:with-param name="depth" select="$depth"/>
        <xsl:with-param name="less" select="$less"/>
      </xsl:apply-templates>
      <!-- back in one <li> to hide some by default  -->
      <xsl:if test="/*/tei:text/tei:back[tei:div[normalize-space(.) != '']|tei:div1[normalize-space(.) != '']]">
        <li class="more">
          <span>
            <xsl:apply-templates select="/*/tei:text/tei:back" mode="title"/>
          </span>
          <ol>
            <xsl:apply-templates select="/*/tei:text/tei:back/*[normalize-space(.) != '']" mode="li">
              <xsl:with-param name="depth" select="$depth"/>
              <xsl:with-param name="less" select="$less"/>
            </xsl:apply-templates>
          </ol>
        </li>
      </xsl:if>
    </ol>
  </xsl:template>
  <xsl:template name="toc-front">
    <xsl:choose>
      <xsl:when test="/*/tei:text/tei:front[tei:div[normalize-space(.) != '']|tei:div1[normalize-space(.) != '']]">
        <ol class="tree">
          <xsl:apply-templates select="/*/tei:text/tei:front/*[self::tei:div|self::tei:div1][normalize-space(.) != '']" mode="li"/>
        </ol>
      </xsl:when>
    </xsl:choose>
  </xsl:template>
  <xsl:template name="toc-back">
    <xsl:choose>
      <xsl:when test="/*/tei:text/tei:back[tei:div[normalize-space(.) != '']|tei:div1[normalize-space(.) != '']]">
        <ol class="tree">
          <xsl:apply-templates select="/*/tei:text/tei:back/*[self::tei:div|self::tei:div1][normalize-space(.) != '']" mode="li"/>
        </ol>
      </xsl:when>
    </xsl:choose>
  </xsl:template>
  <!--
<h3>mode="bibl" (ligne bibliographique)</h3>

<p>
Dégager une ligne biliographique d'un élément, avec des enregistrements bibliographique structurés.
</p>
-->
  <xsl:template match="tei:fileDesc " mode="bibl">
    <!-- titre, requis -->
    <xsl:apply-templates select="tei:titleStmt/tei:title" mode="bibl"/>
    <xsl:if test="tei:titleStmt/tei:principal">
      <!-- direction, requis -->
      <xsl:text>, dir. </xsl:text>
      <xsl:for-each select="tei:titleStmt/tei:principal">
        <xsl:value-of select="."/>
        <xsl:choose>
          <xsl:when test="position() = last()">, </xsl:when>
          <xsl:otherwise>, </xsl:otherwise>
        </xsl:choose>
      </xsl:for-each>
    </xsl:if>
    <!-- édition optionnel -->
    <xsl:if test="tei:editionStmt/@n">
      <xsl:value-of select="tei:editionStmt/@n"/>
      <sup>e</sup>
      <xsl:text> éd., </xsl:text>
    </xsl:if>
    <xsl:variable name="date">
      <!-- date, requis -->
      <xsl:value-of select="tei:publicationStmt/tei:date"/>
      <!-- Collection, optionnel -->
      <xsl:apply-templates select="tei:seriesStmt" mode="bibl"/>
    </xsl:variable>
    <xsl:if test="$date != '' and tei:publicationStmt/tei:idno">
      <xsl:value-of select="$date"/>
      <xsl:text>, </xsl:text>
    </xsl:if>
    <!-- URI de référence, requis -->
    <xsl:apply-templates select="tei:publicationStmt/tei:idno"/>
    <xsl:text>.</xsl:text>
  </xsl:template>
  <!-- Information de série dans une ligne bibliographique -->
  <xsl:template match="tei:seriesStmt" mode="bibl">
    <span class="seriesStmt">
      <xsl:text> (</xsl:text>
      <xsl:for-each select="*[not(@type='URI')]">
        <xsl:apply-templates select="."/>
        <xsl:choose>
          <xsl:when test="position() = last()"/>
          <xsl:otherwise>, </xsl:otherwise>
        </xsl:choose>
      </xsl:for-each>
      <xsl:text>)</xsl:text>
    </span>
  </xsl:template>
  <!-- titre -->
  <xsl:template match="tei:title" mode="bibl">
    <xsl:text> </xsl:text>
    <em class="title">
      <xsl:apply-templates/>
    </em>
  </xsl:template>

</xsl:transform>
