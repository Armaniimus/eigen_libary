<?php
//-- version number 3.0 --//


$newDbConnection = new DB_Primary;
$tableNames = $newDbConnection->tableNames;
$columnNames = $newDbConnection->columnNames;

class DB_Primary {
    public $tableNames;
    public $columnNames;

    public $serverName;
    public $userName;
    public $password;
    public $databaseName;

    function __construct() {
        $this->serverName     = "localhost";
        $this->userName       = "root";
        $this->password       = "";
        $this->databaseName   = "cruddatabase";

        // $serverInfo = ["Servername" => "localhost", "Username" => "root", "Password" => "", "Databasename" => "cruddatabase"]; //Servername, Username, password, dbname
        // echo $this->serverInfo['Servername'];
        // // $serverInfo = [
        // //     "Servername" => "localhost",        // Servername
        // //     "Username" => "root",               // Username
        // //     "Password" => "",                   // Password
        // //     "Databasename" => "cruddatabase"    // Databasename
        // // ];

        $this->tableNames = $this->getTableNames();

        $this->columnNames = [];
        for ($i=0; $i<count($this->tableNames); $i++) {
            $this->columnNames[$i] = $this->getcolumnNames($this->tableNames[$i]);
        }
    }

    public function connect() {
        //Create connection
        $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->databaseName);

        //Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }


    private function getTableNames() {

        // Gets tablenames from the database
        $conn = $this->connect();
        $sql = "SHOW TABLES FROM " . $this->databaseName;
        $result = $conn->query($sql);

        //Outputs data if information was found
        $tableArray = [];
        if ($result->num_rows > 0) {

            //Writes found data into an array
            $i = 0;
            while ($row = $result->fetch_assoc() ) {
                $tableArray[$i] = $row["Tables_in_" . $this->databaseName ];
                $i++;
            }
            $conn->close();
            return $tableArray;
        }
        else {
            $conn->close();
        }
    }

    private function getcolumnNames($tableName) {

        //Gets column names from the database
        $conn = $this->connect();
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
        else {
            $conn->close();
        }
    }
}

function selectWithCodeFromArray($array, $code) {
    $bC = str_split($code);
    $collN = []; // <--- is used to store the output data
    $y=0; // <--- is used to count in which position the next datapiece needs to go

    for ($i=0; $i<count($array); $i++) {
        if ($bC[$i] == 0) {

        }
        else if ($bC[$i] == 1) {
            $collN[$y] = $array[$i];
            $y++;
        }
        else if ($bC[$i] == 2) {
            //runs till the end of the array and writes everything inside the array
            for ($i=$i; $i<count($array); $i++) {
                $collN[$y] = $array[$i];
                $y++;
            }
        }
        else if ($bC[$i] == 3) {
            //runs till the end of the array and writes nothings
            for ($i=$i; $i<count($array); $i++) {

            }
        }
    }
    return $collN;
}

/*************************************************************************************************
    F05; D:none; S(G)
    Status: Good;
    FunctionDescription: generates the where statment from $_POST and given variable columnNames
    Varables input:
        $columnNames(expects a array of strings with a DB column names in them)
*/
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

/*******************************************************************************
    F06; D:connect(), createWhere(); S(G)
    search words: Get Data Select data
    Status: Good
    Function: Returns a 2 dimensional array with strings from SQL database.
    Varables input:
        $tableName(expects an string with a DB tableName)
        $columnNames(expects a array of strings with a DB column names in them)
        $where(expects a string with a $where statement for the sql DB)
*/
function generate2dArrayFromDB($tableName, $columnNames, $where) {

    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

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
        $i = 0;
        $dataArray[$i] = [];
        for ($ii=0; $ii<count($columnNames); $ii++) {
            $dataArray[$i][$ii] = $columnNames[$ii];
        }
        $i=1;
        while($row = $result->fetch_assoc()) {
            $dataArray[$i] = [];
            for ($ii=0; $ii<count($columnNames); $ii++) {

                $dataArray[$i][$ii] = $row[$columnNames[$ii]];
            }
            $i++;
        }
        return $dataArray;
    }
    $conn->close();
}

/********************************************************************************************
    F07; D:connect(); S(G)
    Status: Good
    FunctionDescription: Returns a array from a specified colom/attribute inside the database.
    Variable input:
        $tableName(expects an string with a sql table name)
        $columnName(expects an string with a sql column name)
*/
function getIndividualAttribute($tableName, $columnName) {

    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

    //Perform Query
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

/**************************************************************************
    F08; D:connect(); S(G)
    Status: Good
    Function: Insert a record into an sql Table
    Variables input:
        $columnNames(needs a array of DB attribute names)
        The data you like to add needs to be inside $_POST['collumnnames']
        $tableName(needs a string of a DB tableName)
*/
function insertIntoDatabase($tableName, $columnNames) {

    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

    //extracts input data from superglobal
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

        } else if ($conn->query($sql) === FALSE) {
            if ($conn->connect_error) {
                $message = ("Connection failed: " . $conn->connect_error);
                echo "<script type='text/javascript'>alert('$message');</script>";
            } else {

                $message = "Error: " . $sql . "<br>" . $conn->error;
                echo "<script type='text/javascript'>alert('$message');</script>";

                $message = "\$conn->query(\$sql) === FALSE No more information is available)";
                echo "<script type='text/javascript'>alert('$message');</script>";
            }
        }

    //if not all fields are filled gives a popup that not all fields are filled
    } if ($test != 'true') {
        $message = "Fill in the whole form";
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    $conn->close();
}

/**********************************************************
    F09; D:connect(); S(999)
    Status: 999 not tested
    Function: Changes a value inside the set SQL database
    Variables input:
        $tableName(needs a string of a DB tableName)
        $set needs a prepared sql command as a string
        $where needs a prepared sql command as a string
*/
function updateDatabase($tableName, $set, $where) {
    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

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

/******************************************
    F10; D:connect(); S(999)
    Status: 999 not tested
    FunctionDescription:
        prepares a simple where statement
    Variables input:
        $whereKey expects a string
        $whereValue expects a string
*/
function simpleWhere($whereKey, $whereValue) {

    //where = this value
    $where = "$whereKey = '$whereValue'";
    return $where;
}

/***************************************
    F11; D:connect(); S(999)
    Status: 999 not tested
    FunctionDescription:
        creates a simple set statements
    Variables input:
        $setKeyColumn expects a string
        $setNewValue expects a string
*/
function updateSet($setKeyColumn, $setNewValue) {

    //creates the set statement
    $set = "$setKeyColumn = '$setNewValue'";
    return $set;
}

/********************************************************
    F12; D:connect(); S(999)
    Status: 999 not tested
    Function: Deletes a record inside the sql database
    Variables input:
        $tableName(needs a string of a DB tableName)
        $where needs a prepared sql command as a string
*/
function deleteRecordInDatabase($tableName, $where) {
    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

    $sql =
    "DELETE FROM $tableName
    WHERE $where";

    if ($conn->query($sql) === TRUE) {
        $sql = "Record Succesvol verwijdert <br>";
    } else {
        $sql = "Record is niet verwijdert " . $conn->error;
    }

    return $sql;
}

/***********************************************************
    F13; D:connect(); S(999)
    Status: 999 not tested
    Function: Changes a value inside the set SQL database
    Variables input:
        $tableName(needs a string of a DB tableName)
        $set needs a prepared sql command as a string
        $where needs to be a number or a numberic string
*/
function updateSingleItemDatabase($tableName, $set, $where) {
    //creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

    if (checkIfINT($where) === false) {
        echo "<script>alert('\$where supplied to updateSingleItemDatabase() is not an integer number')</script>";
        return false;
    }

    $sql =
    "UPDATE $tableName
    SET $set
    WHERE 'id=' $where";

    if ($conn->query($sql) === TRUE) {
        $sql = "Record Succesvol geupdate <br>";
    } else {
        $sql = "Record is niet geupdate " . $conn->error;
    }
    return $sql;
}

/********************************************************************************
    F14; S(G)
    Status: Good
    Function: Test if a var is a number (handy when you want to test for a float)
    Variables input:
        $testVar can be a string or number
*/
function checkIfNumeric($testVar) {
    if (is_numeric($testVar)) {
        return true;
    } else {
        return false;
    }
}

/****************************************************************************************
    F15; S(G)
    Status: Good
    Function: Test if a var is an integer number (handy when you want to test for a INT)
    Variables input:
        $testVar can be a string or number
*/
function checkIfINT($testVar) {
    if (is_numeric($testVar) && floor($testVar) == $testVar) {
        return true;
    } else {
        return false;
    }
}

/*******************************************************************************
    F16; D:connect(), createWhere(); S(G)
    search words: Get Data Select data
    Status: Good
    Function: Returns an array of objects with strings from SQL database.
    Varables input:
        $tableName(expects an string with a DB tableName)
        $columnNames(expects a array of strings with a DB column names in them)
        $where(expects a string with a $where statement for the sql DB)
*/
function generateAssocArray($tableName, $columnNames, $where) {

    ///creates a connection with the Database
    $newDbConnection = new DB_Primary;
    $conn = $newDbConnection->connect();

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
        $i = 0;
        $dataArray[$i] = [];
        while($row = $result->fetch_assoc()) {
            $dataArray[$i] = [];
            for ($ii=0; $ii<count($columnNames); $ii++) {

                $dataArray[$i][$columnNames[$ii]] = $row[$columnNames[$ii]];
            }
            $i++;
        }
        return $dataArray;
    }
    $conn->close();
}
