<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <?php include 'generatetables.php';

        //createTableFromDB4($tableNames[0], $collomNames[0], '');
        //getTableNames();
        $column = getColumnNames($tableNames[0]);

        $data = selectFromDB2($tableNames[0], $columnNames[0]);

        echo "<table border='1'>";
        echo createTableFromDB3($data, 8, 8);
        echo "</table>";

        ?>
    </head>
    <body>

    </body>
</html>

function createTableFromDB3($dataArray, $height) {
    //Generates a table from an array
    $res = "";
    for ($x=1; $x<=$height; $x++) {
        $res .= '<tr>';
        for ($y=0; $y<count($dataArray[$x]); $y++) {
            if (isset($dataArray[$x][$y]) ) {
                $res .= '<td>' . $dataArray[$x][$y] . '</td>';
            } else {
                $res .= '<td></td>';
            }
        }
        $res .= '</tr>';
    }
    return $res;
}
