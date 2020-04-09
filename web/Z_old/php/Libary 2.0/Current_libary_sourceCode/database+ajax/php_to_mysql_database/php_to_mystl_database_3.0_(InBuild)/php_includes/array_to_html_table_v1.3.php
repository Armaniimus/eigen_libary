<?php
//-- version number 1.3 --//

//F01; D:SelectFromDB(); S(G)
//Status: Good
//FunctionDescription:
    //creates table from the dataArray
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
function ArrayToHTMLTable1($dataArray) {

    //opens table;
    $result = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $row=0;
    for ($col=0; $col<count($dataArray[$row]); $col++) {
        $tableHeads .= "<th>" . $dataArray[$row][$col] . "</th>";
    }
    $result .= "<tr>" . $tableHeads . "<tr>";

    //generate table main rows
    $row++;
    for ($row=$row; $row<count($dataArray); $row++) {
        $tableMainRow = "";
        for ($col=0; $col<count($dataArray[$row]); $col++) {
            $tableMainRow .= "<th>" . $dataArray[$row][$col] . "</th>";
        }
        $result .= "<tr>" . $tableMainRow . "</tr>";
    }
    $result .= "</table>";
    return $result;
}

//F02; D:selectFromDB(); S(G)
//Status: Good
//FunctionDescription:
    //creates table from the provided data and adds buttons
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
function ArrayToHTMLTable2($dataArray) {
    //opens table;
    $res = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $row=0;
    for ($col=0; $y<count($dataArray[$row]); $col++) {
        $tableHeads .= "<th>" . $dataArray[$row][$col] . "</th>";
    }
    $tableHeads .= "<th colspan='3'>Buttons</th>";
    $res .= "<tr>" . $tableHeads . "<tr>";

    //creeërt de buttons voor de tabel
    $buttons =
    "<td><button type='submit' form='form1' value='read'>Read</button></td>
    <td><button type='submit' form='form1' value='update'>Update</button></td>
    <td><button type='submit' form='form1' value='delete'>Delete</button></td>";

    //generate table main rows
    $row++;
    for ($row=$row; $row<count($dataArray); $row++) {
        $tableMainRow = "";
        for ($col=0; $col<count($dataArray[$row]); $col++) {
            $tableMainRow .= "<th>" . $dataArray[$row][$col] . "</th>";
        }
        $tableMainRow .= $buttons;
        $res .= "<tr>" . $tableMainRow . "</tr>";

    }
    $res .= "</table>";
    return $res;
}

//F03; D:selectFromDB(); S(G)
//Status: Good
//FunctionDescription:
    //creates table from the provided array can be put inside an existing table
    //but opening an closing <table> tags allways need to be put around this output
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
    //$height(needs a INT)
function ArrayToHTMLTable3($dataArray, $height) {
    //Generates a table from an array
    $res = "";
    for ($row=1; $row<=$height; $row++) {
        $res .= '<tr>';

        //if row data exists populate next row with data
        if (isset($dataArray[$row]) ) {
            for ($col=0; $col<count($dataArray[$row]); $col++) {

                //if col data exists populate next cell with data
                if (isset($dataArray[$row][$col]) ) {
                    $res .= '<td>' . $dataArray[$row][$col] . '</td>';

                //if col data doesn't exists generate an empty cell
                } else {
                    $res .= '<td></td>';
                }
            }

        //if row data doesn't exists generate an empty row
        } else {
            for ($col=0; $col<count($dataArray[0]); $col++) {
                $res .= '<td></td>';
            }
        }
        $res .= '</tr>';

    }
    return $res;
}

//F04; D:none; S(G)
//Status: Good
//FunctionDescription:
    //generates an form from where you can add an article or search the database
//Variable input:
    //$columnNames(expects a Array of strings with a sql column names in them)
function addArticleForm($columnNames) {

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
    $res .=
    '</table>
    <input formname="test" name="select" type="submit" value="Select">
    <input formname="test" name="add" type="submit" value="Add">
    </form>';

    return $res;
}

//F02; D:selectFromDB(); S(G)
//Status: Good
//FunctionDescription:
    //creates table from the provided data and adds buttons
//Variables input:
    //$dataArray(requires an 2dimensional Array with strings in them)
function ArrayToHTMLTable5($dataArray) {

    //opens table;
    $res = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $row = 0;

    for ($col=0; $col<=(count($dataArray[$row])-1); $col++) {
        $tableHeads .= "<th>" . $dataArray[$row][$col] . "</th>";
    }
    $tableHeads .= "<th colspan='" . 2 . "'>Buttons</th>";
    $res .= "<tr>" . $tableHeads . "</tr>";

    //generate table main rows
    $row++;
    for ($row=$row; $row<count($dataArray); $row++) {
        $tableMainRow = "";
        for ($col=0; $col<count($dataArray[$row]); $col++) {
            $tableMainRow .= "<td><input name=". $dataArray[0][$col] . " style='width: 100%; border: none;' value='" . $dataArray[$row][$col] . "'></td>";
        }

        //creeërt de buttons voor de tabel
        $buttons =
        "<td><button class='upd-button' value='update_row" . $row . "'>Update</button></td>
        <td><button class='del-button' value='delete_row" . $row . "'>Delete</button></td>";

        $tableMainRow .= $buttons;
        $res .= "<tr id='row" . $row . "'>" . $tableMainRow . "</tr>";

    }
    $res .= "</table>";

    return $res;
}
