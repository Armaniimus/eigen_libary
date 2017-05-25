<?php
include ('notbuilding.php');

function createTableFromDB2($tableName, $collomNames) {

    //gets data from database
    $result = selectFromDB_($tableName, $collomNames);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {

        //opens table;
        $res = "<table border='1' width='100%'>";

        //generates the tableheads 1 by 1
        $tableHeads = "";
        for ($i=0; $i<count($collomNames); $i++) {
            $tableHeads = $tableHeads . "<th>" . $collomNames[$i] . "</th>";
        }
        $res = $res . "<tr>" . $tableHeads . "</tr>";


        //generates the tablerows 1 by 1;
        while($row = $result->fetch_assoc()) {

            //generates a table row with data
            $tableMainRow = "";
            for ($i=0; $i<count($collomNames) ; $i++) {
                $tableMainRow .= "<td>" . $row[$collomNames[$i]] . "</td>";
            }

            //Creates 3 buttons
            $buttons =
            "<td><button type='submit' form='form1' value='read'>Read</button></td>
            <td><button type='submit' form='form1' value='update'>Update</button></td>
            <td><button type='submit' form='form1' value='delete'>Delete</button></td>";

            //combines the 2 variables into a row
            $res .= "<tr>" . $tableMainRow . $buttons . "</tr>";
        }

        return $res;
    } else {

        //writes a alternative table
        $res = "<table border='1'>
        <tr> <td>0 results</td> </tr>
        </table>";
        return $res;
    }
}

function selectFromDB_($tableName, $collomNames) {

    //creates a connection with the Database
    $conn = connect();

    //Generates FROM sql part
    $commaSeperatedCollomNames = $collomNames[0];
    for ($i=1; $i<count($collomNames); $i++) {
        $commaSeperatedCollomNames .= ", " . $collomNames[$i];
    }
    $where = createWhere($collomNames);

    $sql = "SELECT $commaSeperatedCollomNames
    FROM $tableName
    $where";

    //sends query to the database
    $result = $conn->query($sql);

    $conn->close();
    return $result;
}

function createTableFromDB1($tableName, $collomNames) {

    //gets data from database
    $result = selectFromDB_($tableName, $collomNames);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {

        //opens table;
        $res = "<table border='1' width='100%'>";

        //generates the tableheads 1 by 1
        $tableHeads = "";
        for ($i=0; $i<count($collomNames); $i++) {
            $tableHeads = $tableHeads . "<th>" . $collomNames[$i] . "</th>";
        }
        $res = $res . "<tr>" . $tableHeads . "</tr>";

        //generates the tablerows 1 by 1;
        while($row = $result->fetch_assoc()) {

            //generates a table row with data
            $tableMainRow = "";
            for ($i=0; $i<count($collomNames) ; $i++) {
                $tableMainRow .= "<td>" . $row[$collomNames[$i]] . "</td>";
            }
            $res .= "<tr>" . $tableMainRow . "</tr>";
        }
        //closes table
        $res = $res . "</table>";

        //writes table
        return $res;
    } else {

        //writes a alternative table
        echo "<table border='1'>
        <tr> <td>0 results</td> </tr>
        </table>";
    }
}

function createWhere($collomNames) {

    //extracts data from $_POST
    $extractedPost = array();
    for ($i=0; $i<count($collomNames); $i++) {
        if (isset($_POST[$collomNames[$i] ] ) ) {
            $extractedPost[$i] = $_POST[$collomNames[$i] ];
        }
    }

    //Generates a where statement as long as the array $selectdata is long
    $whereState = "";
    for ($i=0; $i<count($extractedPost); $i++) {

        if (isset($extractedPost[$i] ) ) {
            //if there is no data inside $selectdata then add nothing to the where statement.
            if ($extractedPost[$i] == "") {

            //else if there is Data inside $selectdata but no where statement yet then
            //(set the where statement and add the first condition)
            } else if ($whereState == "") {
                $whereState = " WHERE " . $collomNames[$i] . ' LIKE "%' . $extractedPost[$i] . '%"';

            //else if there is data and an already existing where statement
            } else {
                $whereState .= " AND " . $collomNames[$i] . ' LIKE "%' . $extractedPost[$i] . '%"';
            }
        }
    }
    return $whereState;
}

function addArticleForm($tableName, $collomNames, $start) {

    //opens form and table
    $res =
        '<form name="test" action="" method="POST">
        <table border="1" width="100%"  overflow-x="auto">';

    //generates the tableheads WITHOUT id
    $tableHeads = '';
    for ($i=$start; $i<count($collomNames); $i++) {
        $tableHeads = $tableHeads . "<th>" . $collomNames[$i] . "</th>";
    }
    $res = $res . "<tr>" . $tableHeads . "</tr>";

    //Generates the inputfields start a
    $inputFields = '';
    for ($i=$start; $i<count($collomNames); $i++) {
        $inputFields .= '<td>' . '<input name="' . $collomNames[$i] . '" type="text"> </td>';
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
