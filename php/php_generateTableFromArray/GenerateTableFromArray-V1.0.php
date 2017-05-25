<?php
//-- version number 1.0 --//

//F01; D:SelectFromDB(); S(888)
//Function: creates table from the dataArray
//Status: Good but Not tested throughly; (888)
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
function createTableFromDB1($dataArray) {

    //opens table;
    $result = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $x=0;
    for ($y=0; $y<count($dataArray[$x]); $y++) {
        $tableHeads .= "<th>" . $dataArray[$x][$y] . "</th>";
    }
    $result .= "<tr>" . $tableHeads . "<tr>";

    //generate table main rows
    $x++;
    for ($x=$x; $x<count($dataArray); $x++) {
        $tableMainRow = "";
        for ($y=0; $y<count($dataArray[$x]); $y++) {
            $tableMainRow .= "<th>" . $dataArray[$x][$y] . "</th>";
        }
        $result .= "<tr>" . $tableMainRow . "</tr>";
    }
    $result .= "</table>";
    return $result;
}

//F02; D:selectFromDB(); S(888)
//Status: Good but not tested throughly (888)
//Function: creates table from the provided data and adds buttons
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
function createTableFromDB2($dataArray) {

    //opens table;
    $res = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $x=0;
    for ($y=0; $y<count($dataArray[$x]); $y++) {
        $tableHeads .= "<th>" . $dataArray[$x][$y] . "</th>";
    }
    $tableHeads .= "<th colspan='3'>Buttons</th>";
    $res .= "<tr>" . $tableHeads . "<tr>";

    //creeërt de buttons voor de tabel
    $buttons =
    "<td><button type='submit' form='form1' value='read'>Read</button></td>
    <td><button type='submit' form='form1' value='update'>Update</button></td>
    <td><button type='submit' form='form1' value='delete'>Delete</button></td>";

    //generate table main rows
    $x++;
    for ($x=$x; $x<count($dataArray); $x++) {
        $tableMainRow = "";
        for ($y=0; $y<count($dataArray[$x]); $y++) {
            $tableMainRow .= "<th>" . $dataArray[$x][$y] . "</th>";
        }
        $tableMainRow .= $buttons;
        $res .= "<tr>" . $tableMainRow . "</tr>";

    }
    $res .= "</table>";
    return $res;
}

//F03; D:selectFromDB(); S(G)
//Status: Good but not tested throughly (888).
//Function: creates table from the provided array can be put inside an existing table
    //but opening an closing <table> tags allways need to be put around this output
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
    //$height(needs a INT)
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

//F04; D:none; S(G)
//Status: Good
//Function: generates an form from where you can add an article or search the database
//Variable input:
    //$tableName(expects an string with a sql tableName)
    //$columnNames(expects a Array strings with a sql column names in them)
function addArticleForm($tableName, $columnNames) {

    //opens form and table
    $res =
        '<form name="test" action="" method="POST">
        <table border="1" width="100%"  overflow-x="auto">';

    //generates the tableheads WITHOUT id
    $tableHeads = '';
    for ($i=0; $i<count($columnNames); $i++) {
        $tableHeads .= "<th>" . $columnNames[$i] . "</th>";
    }
    $res .= "<tr>" . $tableHeads . "</tr>";

    //Generates the inputfields start a
    $inputFields = '';
    for ($i=0; $i<count($columnNames); $i++) {
        $inputFields .= '<td>' . '<input name="' . $columnNames[$i] . '" type="text"> </td>';
    }
    $res .= '</tr>' . $inputFields . '</tr>';

    //ends form and adds buttons
    $res .= '
    </table>
    <input formname="test" name="select" type="submit" value="Select">
    <input formname="test" name="add" type="submit" value="Add">
    </form>';

    return $res;
}
?>
