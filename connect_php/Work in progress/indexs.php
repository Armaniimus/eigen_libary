<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Over de Rhein</title>
        <link rel="stylesheet" href="css/Over_de_Rhein.css">
        <?php include 'php/database_connect.php';?>
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
                                <select type="number" name="opdrachtnummer">
                                    <?php
                                    $opdrachtNrs = getIndividualAtribute($tableNames[0], $collomNames[0][0]);
                                    echo $opdrachtNrs;
                                    foreach ($opdrachtNrs as $oNr) {
                                        if ($oNr == 2) {
                                            echo '<option value="' . $oNr . '" selected>' . $oNr . '</option>';
                                        } else {
                                            echo '<option value="' . $oNr . '"> ' . $oNr . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
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
                        if (isset($_POST['opdrachtnummer']) ) {
                            createTableFromDB3($tableNames[1], $collomNames[1], 8, 8, 2, $_POST['opdrachtnummer'], 'opdrachtnummer', 'select');
                        } else {
                            createTableFromDB3($tableNames[1], $collomNames[1], 8, 8, 2, "ISNULL", 'opdrachtnummer', 'select');
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
