<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">
     <?php
        include 'php_functions/database_v2.4.php';
        include 'php_functions/array_to_html_table_v1.3.php';
        include 'php_functions/html_select_element_v1.1.php';
     ?>
     <link href="css/hotel_style.css" rel="stylesheet";
</head>

<body>
    <div class="deelformulierOne">
        <a href="hotel_dbf_1_backup.php">Home</a> <a href="new_hotel.php">Nieuw Hotel</a> <a href='#'>Komt nog</a>
    </div>

    <div class="deelformulierOne">
        <?php

        //create upper form
        echo "<form formname='f1' action='' method='POST'>";
            $dataArray = getIndividualattribute($tableNames[0], $columnNames[0][1]);
            echo "Hotelcode:" . generateHtmlSelect($dataArray, $columnNames[0][1]) .
        "</form>";


        if (isset($_POST[$columnNames[0][1] ] ) ) {
            echo "<form formname='f2' action='' method='POST'>
                <input type='hidden' name='" . $columnNames[0][1] . "' value='" . $_POST[$columnNames[0][1]] . "'>";

                //creates dataArray for the inputfield
                $columnSelect = selCollBinary($columnNames[0], "013");
                $where = createWhere($columnSelect);
                $columnSelect = selCollBinary($columnNames[0], "00001");
                $dataArray = generate2dArrayFromDB($tableNames[0], $columnSelect, $where);

                echo "Aantal sterren: <input type='number' max='5' min='1' name='" . $columnNames[0][4] . "' value= '" . $dataArray[1][0] . "'>
                <input type='submit' name='update' value='update'>
            </form>";
        }
        if (isset($_POST['update'] ) ) {
            $set = updateSet("aantalsterren", $_POST['aantalsterren'] );
            $where = simpleWhere('code', $_POST['code']);
            echo updateDatabase($tableNames[0], $set, $where);
        }
        ?>

    </div>
    <div class="deelformulierTwo">
        <?php
        //create lower table
        $columnSelect = selectWithCodeFromArray($columnNames[0], "02");
        $dataArray = generate2dArrayFromDB($tableNames[0], $columnSelect,"");
        echo ArrayToHTMLTable1($dataArray);
        ?>
    </div>
    <script>
        function submit() {
            document.getElementById('select').submit();
        }
    </script>



</body>
</html>
