<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet exclude-result-prefixes="tei xhtml" version="1.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:mets="http://www.loc.gov/mets/" xmlns:mix="http://www.loc.gov/mix/v20" xmlns:ns="http://www.tei-c.org/ns/1.0" xmlns:premis="info:lc/xmlns/premis-v2" xmlns:tei="http://www.tei-c.org/ns/1.0" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xsi:schemalocation="http://www.loc.gov/mets/ http://www.loc.gov/standards/mets/mets.xsd">
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" encoding="UTF-8" indent="yes" method="html"/>
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
  <xsl:template match="/">
    <h3>
      <xsl:value-of select="tei:TEI/tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:title"/>
    </h3>
    <table>
      <!--ligne actes-->
      <thead>
        <tr>
          <th class="caption"><i>Actes</i></th>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <th class="act" colspan="{$n}">
              <xsl:call-template name="roman">
                <xsl:with-param name="arabic" select="@n"/>
              </xsl:call-template>
            </th>
          </xsl:for-each>
        </tr>
        
        <tr>
          <th class="caption">Nombre moyen de <br/>personnages par scène<br/>dans l'acte</th>
          <xsl:for-each select="//*[@type = 'act'] | //*[@type = 'acte']">
            <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
            <td class="act" colspan="{$n}">
              <xsl:value-of select="format-number(count(.//tei:person) div count(.//tei:listPerson[@type = 'configuration']), '#.#')"/>
            </td>
          </xsl:for-each>
        </tr>
        <!--ligne scènes-->
        <xsl:if test="count(//tei:div[@type = 'scene']) > 0">
          <tr>
            <th class="caption"><i>Scènes</i></th>
            <xsl:for-each select="//tei:div[@type = 'act']">
              <xsl:variable name="actId">
                <xsl:value-of select="@xml:id"/>
              </xsl:variable>
              <xsl:choose>
                <xsl:when test="not(tei:div)">
                  <xsl:variable name="n" select="count(.//tei:listPerson[@type = 'configuration'])"/>
                  <th class="scene" colspan="{$n}"> </th>
                </xsl:when>
                <xsl:otherwise>
                  <xsl:for-each select="//tei:div[@xml:id = $actId]//tei:div[@type = 'scene']">
                    <xsl:variable name="sceneId">
                      <xsl:value-of select="@xml:id"/>
                    </xsl:variable>
                    <xsl:variable name="n" select="count(//tei:div[@xml:id = $sceneId]//tei:listPerson[@type = 'configuration'])"/>
                    <th class="scene" colspan="{$n}">
                      <xsl:value-of select="@n"/>
                    </th>
                  </xsl:for-each>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
          </tr>
        </xsl:if>
        <tr>
          <th class="caption">Type de scène</th>
          <xsl:for-each select="//*[@type = 'configuration']">
            <xsl:variable name="personCount" select="count(.//tei:person)"/>
            <xsl:choose>
              <xsl:when test="$personCount = 1">
                <td class="configuration monolog">1</td>
              </xsl:when>
              <xsl:when test="$personCount = 2">
                <td class="configuration dialog">2</td>
              </xsl:when>
              <xsl:when test="$personCount = 3">
                <td class="configuration trilog">3</td>
              </xsl:when>
              <xsl:otherwise>
                <td class="configuration polylog">+</td>
              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </tr>
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
          <tr>
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
                <xsl:value-of select="following::tei:listPerson/@xml:id"/>
              </xsl:variable>
              <xsl:choose>
                <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)]">
                  <xsl:choose>
                    <xsl:when test="$nextConfId = ''">
                      <xsl:choose>
                        <xsl:when test="//tei:listPerson[@xml:id = $confId]/following::tei:sp[@who = $roleId]">
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'offstage']">
                              <td class="configuration offstage">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration speaking">1</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'dead' or @role = 'unconscious']">
                              <td class="configuration dead">†</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'hidden']">
                              <td class="configuration hidden">c</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration mute">2</td>
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
                              <td class="configuration offstage">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration speaking">1</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:when>
                        <xsl:otherwise>
                          <xsl:choose>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'unconscious' or @role= 'dead']">
                              <td class="configuration dead">†</td>
                            </xsl:when>
                            <xsl:when test=".//tei:person[@corresp = concat('#', $roleId)][@role = 'hidden']">
                              <td class="configuration hidden">c</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="configuration mute">2</td>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:otherwise>
                  </xsl:choose>
                </xsl:when>
                <xsl:otherwise>
                  <td class="configuration absent">0</td>
                </xsl:otherwise>
              </xsl:choose>
            </xsl:for-each>
          </tr>
        </xsl:for-each>

      </tbody>
    </table>
  </xsl:template>
</xsl:stylesheet>
