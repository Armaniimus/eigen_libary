<!doctype html>

<html lang="nl">
<head>
    <meta charset="utf-8">
    <?php
    include 'php_functions/database_v2.4.php';
    include 'php_functions/array_to_html_table_v1.3.php';
    include 'php_functions/html_select_element_v1.1.php';
    ?>
    <link href="css/Hotel_style.css" rel="stylesheet";
</head>

<body>
    <?php
    echo
    "<form action='' method='POST'>
        <table border='1'>
            <tr><td>Code:           </td><td>   <input placeholder='Hotel naam' type='text' name=" . $columnNames[0][1] . ">                        </td></tr>
            <tr><td>Ligging:        </td><td>   <input placeholder='Hotel Adres' type='text' name=" . $columnNames[0][2] . ">                       </td></tr>
            <tr><td>Bouwjaar:       </td><td>   <input placeholder='500-2100' max='2100' min='500' type='number' name=" . $columnNames[0][3] . ">  </td></tr>
            <tr><td>Aantalsterren:  </td><td>   <input placeholder='1-5' min='1' max='5' type='number' name=" . $columnNames[0][4] . ">             </td></tr>
            <tr><td>                </td><td>   <input type='submit' name='add' value='voeg hotel toe'>                                             </td></tr>
        </table>
    </form>";

    if (isset($_POST['add'] ) ) {


        //if codes are the same don't run
        $status = 1;

        if ($_POST[$columnNames[0][4]] < 1 || $_POST[$columnNames[0][4]] > 5) {
            $status = 0;
            $message = 'Geen toelaatbare invoer aantalsterren';

        } else if ($_POST[$columnNames[0][3]] < 500 || $_POST[$columnNames[0][3]] > 2100) {
            $status = 0;
            $message = 'Geen toelaatbare invoer Bouwjaar';
        } else if ($_POST[$columnNames[0][1]] == "") {
            $message = 'Geen invoer bij code';

        } else if ($status == 1) {
            $testArray = getIndividualAttribute($tableNames[0], $columnNames[0][1]);
            for ($i=0; $i<count($testArray); $i++) {
                if ($_POST[$columnNames[0][1] ] == $testArray[$i] ) {
                    $status = 0;
                    $i = count($testArray);
                    $message = 'Er bestaat al een record met deze naam';
                }
            }
        }
        if ($status == 1 && isset($_POST[$columnNames[0][1]]) && $_POST[$columnNames[0][1]] !== "") {
            $columnSelect = selCollBinary($columnNames[0], "02");
            $result = insertIntoDatabase($tableNames[0], $columnSelect);
            if ($result == TRUE) {
                header('Location:  hotel_dbf_1_backup.php');
                exit();
            }
        } else {
            if (isset($message)) {
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        }
    }
    ?>
</body>
</html>
