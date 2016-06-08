<div id="form">
    
    <form method="post" enctype="multipart/form-data">




    <div id="pattern">
        <div class="pattern-wrapper">
            <div class="edit-table">
            <a title="Ajouter une colonne" class="tooltip" id="add_column"><img src="img/addcol.png"/></a>
            <a title="Supprimer une colonne" class="tooltip" id="delete_column"><img src="img/delcol.png"/></a>
            <a title="Ajouter une ligne" class="tooltip" id="add_row"><img src="img/addrow.png"/></a>
            <a title="Supprimer une ligne" class="tooltip" id="delete_row"><img src="img/delrow.png"/></a>
            </div>
           <table class="search pattern">
                <tbody>
                                        <tr id="link" class="hide">
                        <td class="no-border"></td>

                        <td class="no-border"></td>

                        <td>
                            <div>
                                <select name="link0-1">
                                    <option value="liaison">
                                        Acte
                                    </option>

                                    <option value="rupture">
                                        Entracte
                                    </option>
                                </select>
                            </div>
                        </td>

                        <td>
                            <div>
                                <select name="link1-2">
                                    <option value="liaison">
                                        Acte
                                    </option>

                                    <option value="rupture">
                                        Entracte
                                    </option>
                                </select>
                            </div>
                        </td>

                        <td>
                            <div>
                                <select name="link2-3">
                                    <option value="liaison">
                                        Acte
                                    </option>

                                    <option value="rupture">
                                        Entracte
                                    </option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select name="link3-4">
                                    <option value="liaison">
                                        Acte
                                    </option>

                                    <option value="rupture">
                                        Entracte
                                    </option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    <tr id="optional" class="hide">
                        <td style="border-right:none;"></td>

                        <td style="border-right:none;"></td>

                        <td></td>

                        <td><input type="text" name="optional1" value="{1}" size="3"></td>

                        <td><input type="text" name="optional2" value="{1}" size="3"></td>
                        <td><input type="text" name="optional3" value="{1}" size="3"></td>
                    </tr>
                    <tr class="character" id="A">
                        <td class="role hide">A</td>
                        <td class="configuration rA c1"><input type="hidden" value="A" id="A1" name="pattern[A][0]"></td>
                        <td class="configuration rA c2"><input type="hidden" value="A" id="A2" name="pattern[A][1]"></td>
                        <td class="configuration rA c3"><input type="hidden" value="A" id="A3" name="pattern[A][2]"></td>
                    </tr>

                    <tr class="character" id="B">
                        <td class="role hide">B</td>
                        <td class="configuration rB c1"><input type="hidden" value="A" id="B1" name="pattern[B][0]"></td>
                        <td class="configuration rB c2"><input type="hidden" value="A" id="B2" name="pattern[B][1]"></td>
                        <td class="configuration rB c3"><input type="hidden" value="A" id="B3" name="pattern[B][2]"></td>
                    </tr>
                    <tr class="character" id="C">
                        <td class="role hide">C</td>
                        <td class="configuration rC c1"><input type="hidden" value="A" id="C1" name="pattern[C][0]"></td>
                        <td class="configuration rC c2"><input type="hidden" value="A" id="C2" name="pattern[C][1]"></td>
                        <td class="configuration rC c3"><input type="hidden" value="A" id="C3" name="pattern[C][2]"></td>
                    </tr>
                </tbody>
            </table>

            <div class="submit-wrapper">
                <input type="hidden" name="post">
                <input class="submit" type="submit" value="Rechercher">
            </div>
            </div>
    </div>

       
     <div id="settings">
        <div class="settings-wrapper">
            <div class="br-settings"></div>
            <div id="options">
                <input class="option-button" type="checkbox" id="group" name="group">
                <label for="group" class="tooltip" title='"AB/A" trouve "ABC/AB" <br/>("A" et "B" représentent un groupe homogène de personnages dont les entrées et les sorties sont solidaires).'><i class="fa fa-group"></i>Grouper les personnages</label>
                <input class="option-button" type="checkbox" id="ignore_confident" name="ignore_confident">
                <label for="ignore_confident" class="tooltip" title='"AB/A" trouve "AB/AC" si C est un confident.'><i class="fa fa-user-times"></i>Ignorer les confidents</label>
            </div>
            <div id="xpath-play" class="hide">
                <p>Évaluer une requête XPath sur la pièce</p>
                <div id="xpath-fields-play">
                    <input type="text" name="xpathplay[]" class="xpath-field-play">
                </div>
                <div>
                <a title="Ajouter un champ" class="tooltip" id="add_field_play"><i class="fa fa-plus"> </i></a>
                <a title="Supprimer un champ" class="tooltip" id="delete_field_play"><i class="fa fa-minus"> </i></a>
                </div>
            </div>
            <div id="xpath-configuration">
                <p>Évaluer une requête XPath</p>
                <div id="xpath-fields-configuration">
                    <input type="text" name="xpath[xpath-0]" class="xpath-field-configuration">
                </div>
                <div>
                <a title="Ajouter un champ" class="tooltip" id="add_field_configuration"><i class="fa fa-plus"> </i></a>
                <a title="Supprimer un champ" class="tooltip" id="delete_field_configuration"><i class="fa fa-minus"> </i></a>
                </div>
            </div>          
        </div></div>
     </form>

</div>
