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
    <xsl:if test="(tei:sp/@who = following-sibling::tei:*[@type='scene'][1]/tei:sp/@who) or (tei:sp/@who = following-sibling::*[@type = 'scene'][1]/tei:sp/@who) or (tei:sp/@who = following-sibling::*[@type = 'titre2'][1]/tei:sp/@who)"/>
  </xsl:template>
  <!-- fonction "last" : renvoie true si on est dans le dernier acte ou la dernière scène -->
  <!-- fonction role = insère une valeur d'attribut -->
  <!-- stocker un élément dans une variable -->
  <!-- dom evaluate attribute -->
  <xsl:template match="/">
    <h3><xsl:value-of select="tei:TEI/tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:author/tei:surname"/>, <i>
      <xsl:value-of select="tei:TEI/tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:title"/></i> (<xsl:value-of select="substring(//tei:date[@type='created']/@when, 1, 4)"/>)
    </h3>
    
    <table class="result pattern" id="{$basename}">
      <!--ligne actes-->
      <thead>
        <tr class="acts">
          <td class="caption no-border">Actes</td>
          <xsl:for-each select="//tei:body//*[@type = 'act']">
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <xsl:variable name="actId" select="@xml:id"/>
            <td class="act {$actId} {.//tei:listPerson[@type='configuration'][1]/@xml:id}" colspan="{$n}" id="{$actId}">
              <xsl:call-template name="roman">
                <xsl:with-param name="arabic" select="@n"/>
              </xsl:call-template>
            </td>
          </xsl:for-each>
          

        </tr>
        <!--ligne scènes-->
        <xsl:if test="count(//tei:*[@type = 'scene']) > 0">
          <tr id="scenes">
            <td class="caption no-border">Scènes</td>
            <xsl:for-each select="//tei:body//tei:*[@type = 'act']">
              <xsl:variable name="actId">
                <xsl:value-of select="@xml:id"/>
              </xsl:variable>
              
              <xsl:choose>
                <xsl:when test="not(tei:*[@type = 'scene'])">
                  <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
                  <td class="scene" colspan="{$n}" id="{@xml:id}"></td>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:for-each select="//tei:*[@xml:id = $actId]//tei:*[@type = 'scene']">
                    <xsl:variable name="sceneId">
                      <xsl:value-of select="@xml:id"/>
                    </xsl:variable>
                    <xsl:variable name="n" select="count(//tei:*[@xml:id = $sceneId]//tei:listPerson[@type = 'configuration'])"/>
                    <td class="scene {.//tei:listPerson[@type='configuration'][1]/@xml:id}" id="{@xml:id}" colspan="{$n}">
                      <xsl:value-of select="@n"/>
                    </td>
                  </xsl:for-each>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
            
            
          </tr>
        </xsl:if>
      </thead>
      <!--corps du tableau-->
      <tbody>
        <!--<xsl:for-each select="//tei:role[@xml:id]"> classer selon la castList + prendre les role qui n'apparaissent pas encore dans les configurations-->
        <xsl:for-each select="//tei:person[not(preceding::tei:person/@corresp = ./@corresp)]">
          
          <xsl:variable name="roleId">
            <xsl:value-of select="substring(@corresp, 2)"/>
<!--            <xsl:value-of select="@xml:id"/> classer selon la castlist-->
          </xsl:variable>
          <xsl:variable name="role">
            <!--            <xsl:value-of select="."/> classer selon la castlist-->
                        <xsl:value-of select="//tei:role[@xml:id=$roleId]"/>
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
                        <xsl:when test="
                          //tei:listPerson[@xml:id = $confId]/following::tei:sp[@who = $roleId]|
                          //tei:listPerson[@xml:id = $confId]/ancestor::tei:sp[@who = $roleId]">
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
                        <xsl:when test="
                          following::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          following::tei:sp[descendant::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          ancestor::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          ancestor::tei:sp[descendant::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]
                          ">
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
            <!--<td>
              <xsl:value-of select="count(//tei:listPerson
                [@type='configuration']
                [descendant::tei:person[@corresp=concat('#', $roleId)]]
                [
                  (not(preceding::tei:listPerson[@type='configuration'][1]//tei:person[@corresp=concat('#', $roleId)])) or 
                  (@subtype='break') or
                  (./ancestor::tei:div[@type='act']/@xml:id != ./preceding::tei:listPerson[@type='configuration'][1]/ancestor::tei:div[@type='act']/@xml:id)
                ]
                )"/>
              
            </td>-->
            
          </tr>
        </xsl:for-each>
        
        
        
        
        <xsl:for-each select="//tei:person[@n]"><!--[not(@corresp = preceding::tei:person/@corresp)]-->
          <xsl:variable name="roleId">
            <xsl:value-of select="translate(@corresp, '#', '')"/>
          </xsl:variable>
          <xsl:variable name="role">
            <xsl:value-of select="@n"/>
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
                        <xsl:when test="
                          following::tei:sp[@who = $roleId]|
                          ancestor::tei:sp[@who = $roleId]">
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
                        <xsl:when test="
                          following::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          following::tei:sp[descendant::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          ancestor::tei:sp[following::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]|
                          ancestor::tei:sp[descendant::tei:listPerson[@xml:id = $nextConfId]][@who = $roleId]
                          ">
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
            <!--<td>
              <xsl:value-of select="count(//tei:listPerson
                [@type='configuration']
                [descendant::tei:person[@corresp=concat('#', $roleId)]]
                [
                (not(preceding::tei:listPerson[@type='configuration'][1]//tei:person[@corresp=concat('#', $roleId)])) or 
                (@subtype='break') or
                (./ancestor::tei:div[@type='act']/@xml:id != ./preceding::tei:listPerson[@type='configuration'][1]/ancestor::tei:div[@type='act']/@xml:id)
                ]
                )"/>
              
            </td>-->
            
          </tr>
        </xsl:for-each>
        <!-- tableau synthétique : moyenne, type de scène, nombre de personnages dans l'espace principal, caché, hors scène, dans l'espace secondaire   -->
 
        
        
        
        
        <!--ligne scènes-->
        
       
        
      </tbody>
    </table>
    <script type="text/javascript">
      var configurationBreaksPlay = [];
      <xsl:for-each select="//tei:listPerson[@type = 'configuration'][position() > 1]">
        <xsl:variable name="actId">
          <xsl:value-of select="ancestor::tei:*[@type = 'act' or @type='acte']/@xml:id"/>
        </xsl:variable>
        <xsl:variable name="confId">
          <xsl:value-of select="@xml:id"/>
        </xsl:variable>
        <xsl:variable name="previousConfId">
          <xsl:value-of select="preceding::tei:listPerson[@type = 'configuration'][1]/@xml:id"/>
        </xsl:variable>
        <!--    dernière conf des premiers actes    -->
        <xsl:if test="count(//tei:listPerson[following::tei:listPerson[@xml:id = $confId]][ancestor::tei:*[@xml:id = $actId]]) = 0">
          configurationBreaksPlay.push("<xsl:value-of select="@xml:id"/>");          
        </xsl:if>
        <!--    si aucun personnage commun    -->
        <xsl:if test="not(.//tei:person/@corresp = //tei:listPerson[@xml:id = $previousConfId]//tei:person/@corresp)">
          <!--    sauf si subtype=link    -->
          <xsl:if test="not(@subtype and @subtype != 'break')">
            configurationBreaksPlay.push("<xsl:value-of select="@xml:id"/>");
          </xsl:if>
        </xsl:if>
        <!--    si subtype=break    -->
        <xsl:if test="@subtype = 'break'">
          configurationBreaksPlay.push("<xsl:value-of select="@xml:id"/>");
        </xsl:if>
      </xsl:for-each>
      configurationBreaks.push({id:'<xsl:value-of select="$basename"/>', configurations:configurationBreaksPlay});
      
      
    </script>
  </xsl:template>
</xsl:stylesheet>
