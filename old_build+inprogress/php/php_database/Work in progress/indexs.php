<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Over de Rhein</title>
        <link rel="stylesheet" href="css/Over_de_Rhein.css">
        <?php
            include 'php/database_v2.2.php';
            include 'php/generate_table_from_array_v1.0.php';
            include 'php/html_select_element_v1.0.php';
        ?>
    </head>
    <body>
        <div class="row">
            <div class="logo col-3">Rhein</div>
            <div class="col-6"></div>
            <div class="name col-3">Hijstabel</div>
        </div>
        <div class="row">
            <div class="menu col-2">Bestand</div>
            <div class="menu col-2">Info</div>
            <div class="col-8"></div>
        </div>
        <div class="row">
            <div class="col-1"></div>
            <div class="inhoud col-10">

                <table>
                    <form action="" method="POST">
                        <tr class="noborder">
                            <td colspan="4" border="0">
                                Kabelchecklist
                            </td>
                            <td colspan="2">
                                Opdrachtnummer:
                            </td>
                            <td colspan="1">
                                <?php

                                $opdrachtNrs = getIndividualAttribute($tableNames[1], $columnNames[1][1]);
                                echo generateHtmlSelect($opdrachtNrs, $columnNames[1][1], '<select type="number" name="opdrachtnummer">')

                                ?>
                            </td>
                            <td>
                                <input type="submit" name="select" value="Send">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">Zichtbare draadbreuken</th>
                            <th>Afslijping van de aan de buitenzijde gelegen draden</th>
                            <th>Roest en corrosie</th>
                            <th>Verminderde kabel diameter</th>
                            <th>Positie van de meetpunten</th>
                            <th>Totale beoordeling</th>
                            <th>beschadiging en vervormingen</th>
                        </tr>
                        <tr>
                            <th>Aantal met een lengte</th>
                            <th>Aantal met een lengte</th>
                            <th>Mate van beschadiging&#178</th>
                            <th>Mate van beschadiging&#178</th>
                            <th>%</th>
                            <th></th>
                            <th>Mate van beschadiging&#178</th>
                            <th>Type</th>
                        </tr>

                        <?php
                        //generates a large table part inside this table
                        $whereState = createWhere($columnNames[1]);
                        $selColNames = selectWithCodeFromArray($columnNames[1], "002");
                        $dataArray = generate2dArrayFromDB($tableNames[1], $selColNames, $whereState);

                        if (isset($_POST['opdrachtnummer']) ) {
                            $dataArray = generate2dArrayFromDB($tableNames[1], $selColNames, $whereState);
                            echo createTableFromDB3($dataArray, 8);
                        } else {
                            echo createTableFromDB3($dataArray, 8);
                        }
                        ?>

                        <tr>
                            <td colspan="8">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">Datum:</th>
                            <td colspan="2"></td>
                            <th colspan="2">Handtekening:</th>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <th colspan="2">Kabelleverancier</td>
                            <td colspan="2"></td>
                            <th colspan="2">Aantal bedrijfsuren</th>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <th colspan="2">Overige waarnemingen</th>
                            <td colspan="2"></td>
                            <th colspan="2">Redenen voor het afleggen.</th>
                            <td colspan="2"></td>
                        </tr>
                        <tr class="noborder">
                            <td colspan="6">
                                &#178In deze kolom graag aangeven: gering, gemiddeld, hoog, zeer hoog, afleggen
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="col-1"></div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="menu col-2">Exit</div>
        </div>

    </body>
</html>
