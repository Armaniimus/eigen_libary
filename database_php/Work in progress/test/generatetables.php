<?php
//-- Start global variables                  --//
//-- Dependency connect(), getcolumnNames(); --//
//---------------------------------------------//
$serverInfo = ["localhost", "root", "", "project_over_de_rhein"]; //Servername, Username, password, dbname
    //generates table information
    $tableNames = getTableNames();
    $columnNames = [];
    for ($i=0; $i<count($tableNames); $i++) {
        $columnNames[$i] = getColumnNames($tableNames[$i]);
    }

//F01 D:none; S(G)
//Status: Good
//Function: Create connection with the specified database
function connect() {

    //insert the global variables inside this function
    global $serverInfo;

    //Create connection
    $conn = new mysqli($serverInfo[0], $serverInfo[1], $serverInfo[2], $serverInfo[3]);

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

//F02; D:connect(); S(G)
//Status: Good
//Function: Get tablenames out of the Database specified
function getTableNames() {

    //Gets tablenames from the database
    Global $serverInfo;
    $conn = connect();
    $sql = "SHOW TABLES FROM " . $serverInfo[3];
    $result = $conn->query($sql);

    //Outputs data if information was found
    $tableArray = [];
    $i = 0;
    if ($result->num_rows > 0) {

        //Writes found data into an array
        while ($row = $result->fetch_assoc() ) {
            $tableArray[$i] = $row["Tables_in_" . $serverInfo[3] ];
            $i++;
        }
        return $tableArray;
    }
}

//F03; D:connect(); S(G)
//Status: Good
//Function: Get columnnames out of the table specified
function getColumnNames($tableName) {

    //Gets column names from the database
    $conn = connect();
    $sql = "SHOW COLUMNS FROM " . $tableName;
    $result = $conn->query($sql);

    //Outputs data if information was found
    $colArray = array();
    if ($result->num_rows > 0) {
        $i = 0;

        //writes names 1 by 1 into the variable $colarray
        while($row = $result->fetch_assoc() ) {
            $colArray[$i] = $row['Field'];
            $i++;
        }

        $conn->close();
        return $colArray;
    }
}

//supporting functions

//F04; D:none; S(G)
//Status: Good;
//FunctionDescription: generates the where statment from $_POST and given variable columnNames
function createWhere($columnNames) {

    //extracts data from $_POST
    $extractedPost = array();
    for ($i=0; $i<count($columnNames); $i++) {
        if (isset($_POST[$columnNames[$i] ] ) ) {
            $extractedPost[$i] = $_POST[$columnNames[$i] ];
        } else {
            $extractedPost[$i] = '';
        }
    }

    //Generates a where statement as long as the array $selectdata is long
    $whereState = "";
    for ($i=0; $i<count($extractedPost); $i++) {
        if (isset($extractedPost[$i]) ) {
            //if there is no data inside $selectdata then add nothing to the where statement.
            if ($extractedPost[$i] == "") {

            //else if there is Data inside $selectdata but no where statement yet then
            //(set the where statement and add the first condition)
            } else if ($extractedPost[$i] <> "" && $whereState == "") {
                $whereState = " WHERE " . $columnNames[$i] . ' LIKE "%' . $extractedPost[$i] . '%"';

            //else if there is data and an already existing where statement
            } else {
                $whereState .= " AND " . $columnNames[$i] . ' LIKE "%' . $extractedPost[$i] . '%"';
            }
        }
    }
    return $whereState;
}

//F05; D:connect(), createWhere(); S(G)
//Status: Good
//Function: Takes data from SQL database.
function selectFromDB($tableName, $columnNames) {

    //creates a connection with the Database
    $conn = connect();

    //Generates SELECT sql part
    $commaSeperatedcolumnNames = $columnNames[0];
    for ($i=1; $i<count($columnNames); $i++) {
        $commaSeperatedcolumnNames .= ", " . $columnNames[$i];
    }

    //Generates WHERE statement
    $where = createWhere($columnNames);

    //combines SELECT $tableName and WHERE parts to form sql query
    $sql = "SELECT $commaSeperatedcolumnNames
    FROM $tableName
    $where";

    //saves query results
    $result = $conn->query($sql);

    $conn->close();
    return $result;
}

//F05; D:connect(), createWhere(); S(G)
//Status: Good
//Function: Takes data from SQL database.
function selectFromDB2($tableName, $columnNames) {

    //creates a connection with the Database
    $conn = connect();

    //Generates SELECT sql part
    $commaSeperatedcolumnNames = $columnNames[0];
    for ($i=1; $i<count($columnNames); $i++) {
        $commaSeperatedcolumnNames .= ", " . $columnNames[$i];
    }

    //Generates WHERE statement
    $where = createWhere($columnNames);

    //combines SELECT $tableName and WHERE parts to form sql query
    $sql = "SELECT $commaSeperatedcolumnNames
    FROM $tableName
    $where";

    //saves query results
    $result = $conn->query($sql);

    //if there are result continue
    if ($result->num_rows > 0) {

        //generates the arrayHeads 1 by 1
        $dataArray = [];
        $x = 0;
        $dataArray[$x] = [];
        for ($y=0; $y<count($columnNames); $y++) {
            $dataArray[$x][$y] = $columnNames[$y];
        }
        $x=1;
        while($row = $result->fetch_assoc()) {
            $dataArray[$x] = [];
            for ($y=0; $y<count($columnNames); $y++) {

                $dataArray[$x][$y] = $row[$columnNames[$y]];
            }
            $x++;
        }
        return $dataArray;
    }
    $conn->close();
}

//F13; D:selectFromDB(); S(G)
//Status: Good
//Function: creates table from the provided data and adds buttons
function createTableFromDB22($dataArray) {

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

//F12; D:SelectFromDB(); S(G)
//Status: Good
//Function: creates table from the provided data
function createTableFromDB1($dataArray) {

    //opens table;
    $res = "<table border='1' width='100%'>";

    //generates the tableheads 1 by 1
    $tableHeads = "";
    $x=0;
    for ($y=0; $y<count($dataArray[$x]); $y++) {
        $tableHeads .= "<th>" . $dataArray[$x][$y] . "</th>";
    }
    $res .= "<tr>" . $tableHeads . "<tr>";

    //generate table main rows
    $x++;
    for ($x=$x; $x<count($dataArray); $x++) {
        $tableMainRow = "";
        for ($y=0; $y<count($dataArray[$x]); $y++) {
            $tableMainRow .= "<th>" . $dataArray[$x][$y] . "</th>";
        }
        $res .= "<tr>" . $tableMainRow . "</tr>";

    }
    $res .= "</table>";
    return $res;
}

function createTableFromDB33($dataArray, $height) {
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
?>
