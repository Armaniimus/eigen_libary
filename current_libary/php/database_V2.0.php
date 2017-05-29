<?php
//-- version number 2.0  --//


//-- global variables D: connect(), getcolumnNames(), getTableNames() --//
$serverInfo = ["localhost", "root", "", "project_over_de_rhein"]; //Servername, Username, password, dbname
    //generates table information
    $tableNames = getTableNames();
    $columnNames = [];
    for ($i=0; $i<count($tableNames); $i++) {
        $columnNames[$i] = getcolumnNames($tableNames[$i]);
    }

//F01 D:none; S(G)
//Status: Good
//Function: Create connection with the specified database
//Variables input:
    //expects the global variable serverInfo to be set as a array with a 4 positions
        //Servername, Username, password, dbname
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
//Function: returns tablenames out of the Database specified
//Variables input:
    //expects the global variable serverInfo to be set as a array with a 4 positions
        //Servername, Username, password, dbname
    //it also needs the connect function
function getTableNames() {

    //Gets tablenames from the database
    Global $serverInfo;
    $conn = connect();
    $sql = "SHOW TABLES FROM " . $serverInfo[3];
    $result = $conn->query($sql);

    //Outputs data if information was found
    $tableArray = [];
    if ($result->num_rows > 0) {

        //Writes found data into an array
        $i = 0;
        while ($row = $result->fetch_assoc() ) {
            $tableArray[$i] = $row["Tables_in_" . $serverInfo[3] ];
            $i++;
        }
        return $tableArray;
    }
}

//F03; D:connect(); S(G)
//Status: Good
//Function: returns columnnames out of the table specified
//Variables input:
    //$tableName(expects a string of a DB tablename)
function getcolumnNames($tableName) {

    //Gets column names from the database
    $conn = connect();
    $sql = "SHOW COLUMNS FROM " . $tableName;
    $result = $conn->query($sql);

    //Outputs data if information was found
    $colArray = array();
    if ($result->num_rows > 0) {
        $i = 0;

        //writes names 1 by 1 into the variable $colarray
        while($row = $result->fetch_assoc()) {
            $colArray[$i] = $row['Field'];
            $i++;
        }

        $conn->close();
        return $colArray;
    }
}

//F04; D: none; S(G)
//Status: Good
//FunctionDescription: With this function you can choose which column names you want inside an array
//Variables input:
    //$columnNames(expects a array of strings with a DB column names in them)
    //$binaryCode(expects a string with the numbers 0123 in them)
        //this will be converting into a array and gets read out 1 by 1
            //0 means this data will NOT be used;
            //1 means this data will be used;
            //2 means this data and everything behind it will be used;
            //3 means this data and everything behind it will NOT be used;
function selCollBinary($columnNames, $binaryCode) {
    $bC = str_split($binaryCode);
    $collN = [];
    $y = 0;

    for ($i=0; $i<count($columnNames); $i++) {
        if ($bC[$i] == 0) {

        }
        else if ($bC[$i] == 1) {
            $collN[$y] = $columnNames[$i];
            $y++;
        }
        else if ($bC[$i] == 2) {
            for ($i=$i; $i<count($columnNames); $i++) {
                $collN[$y] = $columnNames[$i];
                $y++;
            }
        }
        else if ($bC[$i] == 3) {
            for ($i=$i; $i<count($columnNames); $i++) {
            }
        }
    }
    return $collN;
}

//--

//F05; D:none; S(G)
//Status: Good;
//FunctionDescription: generates the where statment from $_POST and given variable columnNames
//Varables input:
    //$columnNames(expects a array of strings with a DB column names in them)
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

//F06; D:connect(), createWhere(); S(G)
//Status: Good
//Function: Returns a 2 dimensional array with strings from SQL database.
//Varables input:
    //$tableName(expects an string with a DB tableName)
    //$columnNames(expects a array of strings with a DB column names in them)
    //$where(expects a string with a $where statement for the sql DB)
function generate2dArrayFromDB($tableName, $columnNames, $where) {

    //creates a connection with the Database
    $conn = connect();

    //Generates SELECT sql part
    $commaSeperatedcolumnNames = $columnNames[0];
    for ($i=1; $i<count($columnNames); $i++) {
        $commaSeperatedcolumnNames .= ", " . $columnNames[$i];
    }

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

//F07; D:connect(); S(G)
//Status: Good
//FunctionDescription: Returns a array from a specified colom/atribute inside the database.
//Variable input:
    //$tableName(expects an string with a sql table name)
    //$columnName(expects an string with a sql column name)
function getIndividualAtribute($tableName, $columnName) {

    //Perform Query
    $conn = connect();
    $selectData =
    'SELECT ' . $columnName . ' AS "result"' .
    ' FROM ' . $tableName;
    $result = $conn->query($selectData);

    //if there are results then continue
    if ($result->num_rows > 0) {

        //Saves the results row by row.
        $i = 0;
        $resArray = [];
        while($row = $result->fetch_assoc()) {
            $resArray[$i] = $row['result'];
            $i++;
        }

        $conn->close();
        return $resArray;
    }
}

//F08; D:connect(); S(G)
//Status: Good
//Function: Insert a record into an sql Table
//Variables input:
    //$columnNames(needs a array of DB atribute names)
        //The data you like to add needs to be inside $_POST['collumnnames']
    //$tableName(needs a string of a DB tableName)
function insertIntoDatabase($columnNames, $tableName) {

    if (isset($_POST["add"]) ) {
        $conn = connect();

        //extracts input data from superglobal $_POST exept column1
        $addData = array();
        for ($i=0; $i<count($columnNames); $i++) {
            $addData[$i] = $_POST[$columnNames[$i] ];
        }

        //tests if all fields are filled
        $test = "true";
        for ($i=0; $i<count($columnNames); $i++) {
            if ($addData[$i] == "") {
                $test = "false";
            }
        }

        //if tests where succesfull create sql query
        if ($test == 'true') {

            //Generates commaseperated names
            $commaSeperatedcolumnNames = $columnNames[0];
            for ($i=1; $i<count($columnNames); $i++) {
                $commaSeperatedcolumnNames .= "," . $columnNames[$i];
            }

            //Adds datafields till the last datafield is reached
            $article = "'" . $addData[0] . "'";
            for ($i=1; $i<count($columnNames); $i++) {
                $article .= "," . "'" . $addData[$i] . "'";
            }

            //Combines $article, $tableName and $commaSeperatedcolumnNames to create the SQL query
            $sql = "INSERT INTO $tableName ($commaSeperatedcolumnNames)
            VALUES ($article)";

            //if acticle gives a notification back if an article was added successfully
            //and reloads the page
            if ($conn->query($sql) === TRUE) {
                $message = "New record created successfully";
                echo "<script type='text/javascript'>alert('$message');</script>";
                echo "<script type='text/javascript'>('window.location.reload();')</script>";
            }

        //if not all fields are filled gives a popup that not all fields are filled
        } else {
            $message = "Fill in the whole form";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }

        $conn->close();
    }
}

//--

//F09; D:connect(); S(999)
//Status: 999 not tested
//Function: Changes a value inside the set SQL database
//Variables input:
    //$tableName(needs a string of a DB tableName)
    //$set needs a prepared sql command as a string
    //$where needs a prepared sql command as a string
function updateDatabase($tableName, $set, $where) {
    $conn = connect();

    $sql =
    "UPDATE $tableName
    SET $set
    WHERE $where";

    if ($conn->query($sql) === TRUE) {
        $sql = "Record Succesvol geupdate <br>";
    } else {
        $sql = "Record is niet geupdate " . $conn->error;
    }

    return $sql;
}

//F10; D:connect(); S(999)
//Status: 999 not tested
//FunctionDescription:
    //prepares a simple where statement
//Variables input:
    //$whereKey expects a string
    //$whereValue expects a string
function simpleWhere($whereKey, $whereValue) {

    //where = this value
    $where = "$whereKey = '$whereValue'";
    return $where;
}

//F11; D:connect(); S(999)
//Status: 999 not tested
//FunctionDescription:
    //creates a simple set statementS
//Variables input:
    //$setKeyColumn expects a string
    //$setNewValue expects a string
function updateSet($setKeyColumn, $setNewValue) {

    //creates the set statement
    $set = "$setKeyColumn = '$setNewValue'";
    return $set;
}

//F12; D:connect(); S(999)
//Status: 999 not tested
//Function: Deletes a record inside the sql database
//Variables input:
    //$tableName(needs a string of a DB tableName)
    //$where needs a prepared sql command as a string
function deleteRecordInDatabase($tableName, $set, $where) {
    $conn = connect();

    $sql =
    "DELETE $tableName
    WHERE $where";

    if ($conn->query($sql) === TRUE) {
        $sql = "Record Succesvol verwijdert <br>";
    } else {
        $sql = "Record is niet verwijdert " . $conn->error;
    }

    return $sql;
}


?>
