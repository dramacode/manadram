<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet exclude-result-prefixes="tei xhtml" version="1.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:mets="http://www.loc.gov/mets/" xmlns:mix="http://www.loc.gov/mix/v20" xmlns:ns="http://www.tei-c.org/ns/1.0" xmlns:premis="info:lc/xmlns/premis-v2" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xsi:schemalocation="http://www.loc.gov/mets/ http://www.loc.gov/standards/mets/mets.xsd">
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" encoding="UTF-8" indent="yes" method="html"/>
  <xsl:param name="basename"/>
  <!-- fonction "romain" : affiche un chiffre arabe en chiffre romain -->
  <xsl:variable name="lowercase" select="'abcdefghijklmnopqrstuvwxyzéèê'"/>
  <xsl:variable name="uppercase" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZÉÈÊ'"/>
  <xsl:template name="roman">
    <xsl:param name="arabic"/>
    <xsl:choose>
      <xsl:when test="$arabic = 1">I</xsl:when>
      <xsl:when test="$arabic = 2">II</xsl:when>
      <xsl:when test="$arabic = 3">III</xsl:when>
      <xsl:when test="$arabic = 4">IV</xsl:when>
      <xsl:when test="$arabic = 5">V</xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$arabic"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  <!-- fonction "link" : renvoie true ou false selon qu'une scène est lié à la suivante -->
  <xsl:template name="link">
    <xsl:param name="scene"/>
    <xsl:if test="(tei:sp/@who = following-sibling::tei:div2[1]/tei:sp/@who) or (tei:sp/@who = following-sibling::*[@type = 'scene'][1]/tei:sp/@who) or (tei:sp/@who = following-sibling::*[@type = 'titre2'][1]/tei:sp/@who)"/>
  </xsl:template>
  <!-- fonction "last" : renvoie true si on est dans le dernier acte ou la dernière scène -->
  <!-- fonction role = insère une valeur d'attribut -->
  <!-- stocker un élément dans une variable -->
  <!-- dom evaluate attribute -->
  <xsl:template match="/">
    <h3>
      <xsl:value-of select="tei:TEI/tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:title"/>
    </h3>
    <table id="{$basename}">
      <!--ligne actes-->
      <thead>
        <tr id="acts">
          <th class="caption">Actes</th>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <xsl:variable name="actId" select="@xml:id"/>
            <th class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="{$actId}">
              <xsl:call-template name="roman">
                <xsl:with-param name="arabic" select="@n"/>
              </xsl:call-template>
            </th>
          </xsl:for-each>
        </tr>
        <!--ligne scènes-->
        <xsl:if test="count(//tei:div[@type = 'scene']) > 0">
          <tr id="scenes">
            <th class="caption">Scènes</th>
            <xsl:for-each select="//tei:div[@type = 'act']">
              <xsl:variable name="actId">
                <xsl:value-of select="@xml:id"/>
              </xsl:variable>
              <xsl:choose>
                <xsl:when test="not(tei:div)">
                  <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
                  <th class="scene" colspan="{$n}" id="{@xml:id}"> </th>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:for-each select="//tei:div[@xml:id = $actId]//tei:div[@type = 'scene']">
                    <xsl:variable name="sceneId">
                      <xsl:value-of select="@xml:id"/>
                    </xsl:variable>
                    <xsl:variable name="n" select="count(//tei:div[@xml:id = $sceneId]//tei:listPerson[@type = 'configuration'])"/>
                    <th class="scene {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}">
                      <xsl:value-of select="@n"/>
                    </th>
                  </xsl:for-each>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
          </tr>
        </xsl:if>
      </thead>
      <!--corps du tableau-->
      <tbody>
        <xsl:for-each select="//tei:person[not(@corresp = preceding::tei:person/@corresp)]">
          <xsl:variable name="roleId">
            <xsl:value-of select="translate(@corresp, '#', '')"/>
          </xsl:variable>
          <xsl:variable name="role">
            <xsl:choose>
              <xsl:when test="@n">
                <xsl:value-of select="@n"/>
              </xsl:when>
              <xsl:otherwise>
                <xsl:value-of select="//tei:role[@xml:id = $roleId]/text()"/>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:variable>
          <tr id="{$roleId}">
            <!--colonne role-->
            <td class="role">
              <xsl:value-of select="$role"/>
            </td>
            <!--colonne configuration-->
            <xsl:for-each select="//tei:listPerson[@type = 'configuration']">
              <xsl:variable name="confId">
                <xsl:value-of select="@xml:id"/>
              </xsl:variable>
              <xsl:variable name="nextConfId">
                <xsl:value-of select="following::tei:listPerson[@type = 'configuration']/@xml:id"/>
              </xsl:variable>
              <xsl:choose>
                <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)]">
                  <xsl:choose>
                    <xsl:when test="$nextConfId = ''">
                      <xsl:choose>
                        <xsl:when test="//tei:listPerson[@xml:id = $confId]/following::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]">
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'offstage']">
                              <td class="configuration offstage {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'aside']">
                              <td class="configuration aside speaking {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration speaking {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'unconscious' or @role = 'dead']">
                              <td class="configuration dead {$confId}" id="{$roleId}{$confId}">†</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'hidden']">
                              <td class="configuration hidden {$confId}" id="{$roleId}{$confId}">c</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'aside']">
                              <td class="configuration aside mute {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration mute {$confId}" id="{$roleId}{$confId}">2</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:when>
                    <xsl:otherwise>
                      <xsl:choose>
                        <xsl:when test="//tei:listPerson[@xml:id = $confId]/following::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]">
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'offstage']">
                              <td class="configuration offstage {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'aside']">
                              <td class="configuration aside speaking {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration speaking {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'unconscious' or @role = 'dead']">
                              <td class="configuration dead {$confId}" id="{$roleId}{$confId}">†</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'hidden']">
                              <td class="configuration hidden {$confId}" id="{$roleId}{$confId}">c</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'aside']">
                              <td class="configuration aside mute {$confId}" id="{$roleId}{$confId}">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration mute {$confId}" id="{$roleId}{$confId}">2</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:otherwise>
                  </xsl:choose>
                </xsl:when>
                <xsl:otherwise>
                  <td class="configuration absent {$confId}" id="{$roleId}{$confId}">0</td>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
          </tr>
        </xsl:for-each>
        <!-- tableau synthétique : moyenne, type de scène, nombre de personnages dans l'espace principal, caché, hors scène, dans l'espace secondaire   -->
        <tr>
          <td> </td>
        </tr>
        <tr id="averagePresents">
          <td class="role">Nombre moyen de personnages<br/> présents par scène dans l'acte</td>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="actId" select="@xml:id"/>
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <td class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="averagePresents{$actId}">
              <xsl:value-of select="format-number(count(.//tei:person) div count(.//tei:listPerson[@type = 'configuration']), '#.#')"/>
            </td>
          </xsl:for-each>
        </tr>
        <tr id="averageSpeakings">
          <td class="role">Nombre moyen de personnages<br/> parlant par scène dans l'acte</td>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="actId" select="@xml:id"/>
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <td class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="averageSpeaking{$actId}"> </td>
          </xsl:for-each>
        </tr>
        <tr id="averagePresentsTime">
          <td class="role">Nombre moyen de personnages<br/> présents par scène dans l'acte<br/> rapporté à la durée de la scène</td>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="actId" select="@xml:id"/>
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <td class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="averagePresentsTime{$actId}">   </td>
          </xsl:for-each>
        </tr>
        <tr id="averageSpeakingsTime">
          <td class="role">Nombre moyen de personnages <br/>parlant par scène dans l'acte<br/> rapporté à la durée de la scène</td>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="actId" select="@xml:id"/>
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <td class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="averageSpeakingsTime{$actId}"> </td>
          </xsl:for-each>
        </tr>
        <!--ligne scènes-->
        <tr id="numberPresents">
          <td class="role">Personnages présents</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <td class="configuration ">
              <xsl:value-of select="count(.//tei:person)"/>
            </td>
          </xsl:for-each>
        </tr>
        <tr id="numberSpeakings">
          <td class="role">Personnages parlant</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="confId">
              <xsl:value-of select="@xml:id"/>
            </xsl:variable>
            <xsl:variable name="nextConfId">
              <xsl:value-of select="following::tei:listPerson/@xml:id"/>
            </xsl:variable>
            <xsl:variable name="personCount">
              <xsl:choose>
                <xsl:when test="$nextConfId = ''">
                  <xsl:value-of select="count(//tei:listPerson[@xml:id = $confId]//tei:person[translate(@corresp, '#', '') = //tei:listPerson[@xml:id = $confId]/following::tei:sp/@who])"/>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:value-of select="count(//tei:listPerson[@xml:id = $confId]//tei:person[translate(@corresp, '#', '') = //tei:listPerson[@xml:id = $confId]/following::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]]/@who])"/>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:variable>
            <xsl:choose>
              <xsl:when test="$personCount = 0">
                <td class="configuration {$confId}"> –<!--<xsl:value-of select="$personCount"/>--> </td>
              </xsl:when>
              <xsl:when test="$personCount = 1">
                <td class="configuration monolog {$confId}">
                  <xsl:value-of select="$personCount"/>
                </td>
              </xsl:when>
              <xsl:when test="$personCount = 2">
                <td class="configuration dialog {$confId}">
                  <xsl:value-of select="$personCount"/>
                </td>
              </xsl:when>
              <xsl:when test="$personCount = 3">
                <td class="configuration trilog {$confId}">
                  <xsl:value-of select="$personCount"/>
                </td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration polylog {$confId}">
                  <xsl:value-of select="$personCount"/>
                </td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
        <tr id="numberFrontStage">
          <td class="role">Personages de l'espace principal</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="confId">
              <xsl:value-of select="@xml:id"/>
            </xsl:variable>
            <xsl:variable name="count" select="count(.//tei:person[not(@role = 'hidden')][not(@role = 'aside')][not(@role = 'offstage')])"/>
            <xsl:choose>
              <xsl:when test="$count > 0">
                <td class="configuration dead {$confId}">
                  <xsl:value-of select="$count"/>
                </td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration {$confId}"> </td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
        <tr id="numberAside">
          <td class="role">Personages de l'espace secondaire</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="confId">
              <xsl:value-of select="@xml:id"/>
            </xsl:variable>
            <xsl:variable name="count" select="count(.//tei:person[@role = 'aside'])"/>
            <xsl:choose>
              <xsl:when test="$count > 0">
                <td class="configuration dead {$confId}">
                  <xsl:value-of select="$count"/>
                </td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration {$confId}"> </td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
        <tr id="numberHidden">
          <td class="role">Personages cachés</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="confId">
              <xsl:value-of select="@xml:id"/>
            </xsl:variable>
            <xsl:variable name="count" select="count(.//tei:person[@role = 'hidden'])"/>
            <xsl:choose>
              <xsl:when test="$count > 0">
                <td class="configuration dead {$confId}">
                  <xsl:value-of select="$count"/>
                </td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration {$confId}"> </td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
        <tr id="numberOffstage">
          <td class="role">Personages hors scène</td>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="confId">
              <xsl:value-of select="@xml:id"/>
            </xsl:variable>
            <xsl:variable name="count" select="count(.//tei:person[@role = 'offstage'])"/>
            <xsl:choose>
              <xsl:when test="$count > 0">
                <td class="configuration dead {$confId}">
                  <xsl:value-of select="$count"/>
                </td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration {$confId}"> </td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
      </tbody>
    </table>
    <script type="text/javascript">
<!--
        pb si j 'ai plusieurs tables//-->
      var configurationBreaks = new Array();
      <xsl:for-each select="//tei:listPerson[@type = 'configuration'][position() > 1]">
        <xsl:variable name="actId">
          <xsl:value-of select="ancestor::tei:div[@type = 'act']/@xml:id"/>
        </xsl:variable>
        <xsl:variable name="confId">
          <xsl:value-of select="@xml:id"/>
        </xsl:variable>
        <xsl:variable name="previousConfId">
          <xsl:value-of select="preceding::tei:listPerson[@type = 'configuration'][1]/@xml:id"/>
        </xsl:variable>
         <!--    dernière conf des premiers actes    -->
        <xsl:if test="count(//tei:listPerson[following::tei:listPerson[@xml:id = $confId]][ancestor::tei:div[@xml:id = $actId]]) = 0">
          configurationBreaks.push("<xsl:value-of select="@xml:id"/>");          
        </xsl:if>
        <!--    si aucun personnage commun    -->
        <xsl:if test="not(//tei:listPerson[@xml:id = $confId]//tei:person/@corresp = //tei:listPerson[@xml:id = $previousConfId]//tei:person/@corresp)">
          <!--    sauf si subtype=link    -->
          <xsl:if test="not(@subtype and @subtype != 'break')">
            configurationBreaks.push("<xsl:value-of select="@xml:id"/>");
          </xsl:if>
        </xsl:if>
<!--    si subtype=break    -->
        <xsl:if test="@subtype = 'break'">
          configurationBreaks.push("<xsl:value-of select="@xml:id"/>");
        </xsl:if>
      </xsl:for-each>
<!--
        alert(configurationBreaks);//--><!--
        appliquer aux conf//-->
<!--
        appliquer aux scènes//-->
<!--
        appliquer aux actes: tous sauf le premier//-->
    </script>
  </xsl:template>
</xsl:stylesheet>
