<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:tei="http://www.tei-c.org/ns/1.0">

  <xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML//EN"
    doctype-system="http://www.w3.org/TR/2001/REC-xhtml11-20010531" indent="yes"/>
  <!-- fonction "romain" : affiche un chiffre arabe en chiffre romain -->
  <xsl:variable name="lowercase" select="'abcdefghijklmnopqrstuvwxyzéèê'"/>
  <xsl:variable name="uppercase" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZÉÈÊ'"/>
  <xsl:template name="romain">
    <xsl:param name="arabe"/>
    <xsl:choose>
      <xsl:when test="$arabe=1">I</xsl:when>
      <xsl:when test="$arabe=2">II</xsl:when>
      <xsl:when test="$arabe=3">III</xsl:when>
      <xsl:when test="$arabe=4">IV</xsl:when>
      <xsl:when test="$arabe=5">V</xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$arabe"/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  <!-- fonction "liaison" : renvoie true ou false selon qu'une scène est lié à la suivante -->
  <xsl:template name="liaison">
    <xsl:param name="scène"/>
    <xsl:if test="sp/@who = following-sibling::div2[1]/sp/@who"></xsl:if>
  </xsl:template>
  <!-- fonction "dernier" : renvoie true si on est dans le dernier acte ou la dernière scène -->
  <xsl:template match="/">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>
          <xsl:value-of select="TEI.2/teiHeader/fileDesc/titleStmt/title"/>
        </title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
      </head>
      <body>
        <table>
          <!--ligne actes-->
          <tr>
            <td rowspan="2" class="vide"/>
            <xsl:for-each select="//div1">
                        <xsl:choose>
            <xsl:when test="@type = 'acte'">

              <xsl:variable name="n" select="count(div2)"/>
              <xsl:choose>
                <!--si on est dans le dernier acte-->
                <xsl:when test="position() = last()">
                  <td class="acte" colspan="{$n}">
                    <xsl:call-template name="romain">
                      <xsl:with-param name="arabe" select="@n"/>
                    </xsl:call-template>
                  </td>
                </xsl:when>
                <!--si on est dans les actes précédents-->
                <xsl:otherwise>
                  <td class="acte last" colspan="{$n}">
                    <!--<xsl:value-of select="@n"/>-->
                    <xsl:call-template name="romain">
                      <xsl:with-param name="arabe" select="@n"/>
                    </xsl:call-template>
                  </td>
                </xsl:otherwise>
              </xsl:choose>
<!-- fin de l'exclusion des annexes-->
            </xsl:when>
             <xsl:otherwise>
                </xsl:otherwise>
             </xsl:choose>

            </xsl:for-each>
          </tr>
          <!--ligne scènes-->
          <tr>
            <xsl:for-each select="//div1">
                          <xsl:choose>
            <xsl:when test="@type = 'acte'">
            <xsl:choose>
                <!--si on est dans le dernier acte-->
                <xsl:when test="position() = last()">
                  <xsl:for-each select="div2">
                    <xsl:choose>
                      <!-- si on n'est pas dans la dernière scène, lancer le test "liée à la suivante"                      -->
                      <xsl:when test="position() != last()">
                        <xsl:choose>
                          <!--  si la scène est liée la suivante : affichage normal                   -->
                          <xsl:when test="sp/@who = following-sibling::div2[1]/sp/@who">
                            <td>
                              <xsl:value-of select="@n"/></td>
                          </xsl:when>
                          <xsl:otherwise>
                            <!-- si la scène est liée à la suivante : class break                       -->
                            <td class="rupture">
                              <xsl:value-of select="@n"/></td>
                          </xsl:otherwise>
                        </xsl:choose>
                      </xsl:when>
                      <!-- sinon afficher normalement                     -->
                      <xsl:otherwise>
                        <td>
                          <xsl:value-of select="@n"/></td>
                      </xsl:otherwise>
                    </xsl:choose>

                  </xsl:for-each>
                </xsl:when>
                <!--si on est dans les actes précédents-->
                <xsl:otherwise>
                  <xsl:for-each select="div2">
                    <xsl:choose>
                      <!--si c'est la dernière scène class rupture direct-->
                      <xsl:when test="position() = last()">
                        <td class="rupture">
                          <xsl:value-of select="@n"/>
                        </td>
                      </xsl:when>
                      <!--si c'est une scène précédente lancer le test de liaison-->
                      <xsl:otherwise>
                        <xsl:choose>
                          <!--  si la scène est liée la suivante : affichage normal                   -->
                          <xsl:when test="sp/@who = following-sibling::div2[1]/sp/@who">
                            <td>
                              <xsl:value-of select="@n"/>
                            </td>
                          </xsl:when>
                          <xsl:otherwise>
                            <!-- si la scène est liée à la suivante : class rupture                       -->
                            <td class="rupture">
                              <xsl:value-of select="@n"/>
                            </td>
                          </xsl:otherwise>
                        </xsl:choose>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:for-each>
                </xsl:otherwise>
              </xsl:choose>
  <!-- fin de l'exclusion des annexes-->
            </xsl:when>
             <xsl:otherwise>
                </xsl:otherwise>
             </xsl:choose>
                       </xsl:for-each>
          </tr>

          <!--corps du tableau-->

          <xsl:for-each select="//role">
            <xsl:variable name="role" select="@id"/>
           <xsl:variable name="personnage" select="."/>
            <tr>
 
              <!--colonne role-->
              <td class="role">
                <xsl:value-of select="$personnage"/>
              </td>

              <!--colonne scène-->
              <xsl:for-each select="//div1">
              
                                      <xsl:choose>
            <xsl:when test="@type = 'acte'">

                <xsl:choose>
                  <!--si on est dans le dernier acte-->
                  <xsl:when test="position() = last()">

                    <xsl:for-each select="div2">
                      <xsl:variable name="head" select="translate(head, $lowercase, $uppercase)"/>
                      <xsl:choose>
                        <!-- si on n'est pas dans la dernière scène, lancer le test "liée à la suivante"                      -->
                        <xsl:when test="position() != last()">
                          <xsl:choose>
                            <!--  si la scène est liée la suivante : affichage normal                   -->
                            <xsl:when test="sp/@who = following-sibling::div2[1]/sp/@who">
                              <xsl:choose>
                                
                                <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                                  <td class="muet">0</td>
                                </xsl:when>
                                <xsl:when test="count(sp[@who=$role]) > 0">
                                  <td class="parlant">1</td>
                                </xsl:when>
                                <xsl:otherwise>
                                  <td class="absent"/>
                                </xsl:otherwise>
                              </xsl:choose>
                            </xsl:when>
                            <xsl:otherwise>
                              <!-- si la scène est liée à la suivante : class rupture                       -->
                              <xsl:choose>
                                <xsl:when
                                  test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                                  <td class="muet rupture">0</td>
                                </xsl:when>
                                <xsl:when test="count(sp[@who=$role]) > 0">
                                  <td class="parlant rupture">1</td>
                                </xsl:when>
                                <xsl:otherwise>
                                  <td class="absent rupture"/>
                                </xsl:otherwise>
                              </xsl:choose>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:when>
                        <!-- sinon afficher normalement                     -->
                        <xsl:otherwise>
                          <xsl:choose>
                            <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                              <td class="muet">0</td>
                            </xsl:when>
                            <xsl:when test="count(sp[@who=$role]) > 0">
                              <td class="parlant">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="absent"/>
                            </xsl:otherwise>
                          </xsl:choose>
                        </xsl:otherwise>
                      </xsl:choose>

                      <!--                    <xsl:variable name="head" select="translate(head, $lowercase, $uppercase)"/>
                      <xsl:choose>
                        <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                          <td class="muet">0</td>
                        </xsl:when>
                        <xsl:when test="count(sp[@who=$role]) > 0">
                          <td class="parlant">1</td>
                        </xsl:when>
                        <xsl:otherwise>
                          <td class="absent"/>
                        </xsl:otherwise>
                      </xsl:choose>
-->
                    </xsl:for-each>

                  </xsl:when>
                  <xsl:otherwise>
                    <!--si on est ds les actes précédents-->
                    <xsl:for-each select="div2">
                      <xsl:variable name="head" select="translate(head, $lowercase, $uppercase)"/>
                      <xsl:choose>

                        <!--si c'est la dernière scène : class rupture direct-->
                        <xsl:when test="position() = last()">

                          <xsl:choose>
                            <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                              <td class="muet rupture">0</td>
                            </xsl:when>
                            <xsl:when test="count(sp[@who=$role]) > 0">
                              <td class="parlant rupture">1</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="absent rupture"/>
                            </xsl:otherwise>
                          </xsl:choose>


                        </xsl:when>

                        <!--si c'est une scène précédente, lancer le test de liaison-->
                        <xsl:otherwise>
                          <xsl:choose>
                            <!--  si la scène est liée la suivante : affichage normal                   -->
                            <xsl:when test="sp/@who = following-sibling::div2[1]/sp/@who">
                              <xsl:choose>
                                
                                <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                                  <td class="muet">0</td>
                                </xsl:when>
                                <xsl:when test="count(sp[@who=$role]) > 0">
                                  <td class="parlant">1</td>
                                </xsl:when>
                                <xsl:otherwise>
                                  <td class="absent"/>
                                </xsl:otherwise>
                              </xsl:choose>
                            </xsl:when>
                            <xsl:otherwise>
                              <!-- si la scène est liée à la suivante : class rupture                       -->
                              <xsl:choose>
                                <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                                  <td class="muet rupture">0</td>
                                </xsl:when>
                                <xsl:when test="count(sp[@who=$role]) > 0">
                                  <td class="parlant rupture">1</td>
                                </xsl:when>
                                <xsl:otherwise>
                                  <td class="absent rupture"/>
                                </xsl:otherwise>
                              </xsl:choose>
                            </xsl:otherwise>
                          </xsl:choose>
 <!--                         <xsl:choose>
                            <xsl:when test="contains($head, $personnage) and count(sp[@who=$role]) = 0">
                              <td class="muet">a</td>
                            </xsl:when>
                            <xsl:when test="count(sp[@who=$role]) > 0">
                              <td class="parlant">p</td>
                            </xsl:when>
                            <xsl:otherwise>
                              <td class="absent"/>
                            </xsl:otherwise>
                          </xsl:choose>-->

                        </xsl:otherwise>
                      </xsl:choose>
                    </xsl:for-each>

                  </xsl:otherwise>
                </xsl:choose>
  <!-- fin de l'exclusion des annexes-->
            </xsl:when>
             <xsl:otherwise>
                </xsl:otherwise>
             </xsl:choose>
             </xsl:for-each>
            </tr>
  
          </xsl:for-each>
        </table>
<!--        <br/>Les scènes suivantes sont séparées par une rupture :<br/>
        <xsl:for-each select="//div1">
          <xsl:for-each
            select="div2[not(position() = last())][not(sp/@who = following-sibling::div2[1]/sp/@who)]">
            <xsl:call-template name="romain"><xsl:with-param name="arabe" select="../@n"
              /></xsl:call-template>, <xsl:value-of select="@n"/>/<xsl:value-of
              select="following-sibling::div2[1]/@n"/><br/>
          </xsl:for-each>
        </xsl:for-each>
    -->
        <br/>
        <br/>
        <table><tr><td>Personnage présent</td><td class="parlant">1</td></tr><tr><td>Personnage muet</td><td class="muet">Personnage muet</td></tr></table>        
        <br/>
        <!--<table><td class="rupture">Scène suivie d'une rupture</td></table>
        <br/><table><td class="absent">Scène suivie d'une liaison</td></table>-->
        <br/>
    <p><a href="./">Revenir à l'accueil</a></p>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
<!--
1° TABLEAU
ne pas passer par role, mais uniquement par sp@who (for each new)
Personnages classés par ordre d'apparition
dans le fichier TEI, mettre pour chaque scène la liste des personnages avec leur statut
faire une fonction "test de liaison" et une fonction "test de présence"
2° RUPTURES
compter
prendre en compte les muets

3° PARCOURIR UN DOSSIER
parser sans stylesheet
-->
