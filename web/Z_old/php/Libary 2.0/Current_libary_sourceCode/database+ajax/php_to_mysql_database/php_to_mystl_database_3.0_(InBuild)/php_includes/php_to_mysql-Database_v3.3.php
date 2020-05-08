<?php
//-- version number 3.2 --//

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

        $this->tableNames = $this->GetTableNames();
        $this->columnNames = [];
        for ($i=0; $i<count($this->tableNames); $i++) {
            $this->columnNames[$i] = $this->GetColumnNames($this->tableNames[$i]);
        }
    }

    /**************************************************************************************
        F01 D:none; S(G)
        Status: Good
        Function: Create connection with the specified database
        Variables input:
            expects the global variable serverInfo to be set as a array with a 4 positions
                Servername, Username, password, dbname
    */
    public function Connect() {
        //Create connection
        $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->databaseName);

        //Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    /***************************************************************************************
        F02; D:connect(); S(G)
        Status: Good
        Function: returns tablenames out of the Database specified
        Variables input:
            expects the global variable serverInfo to be set as a array with a 4 positions
                Servername, Username, password, dbname
            it also needs the connect function
    */
    private function GetTableNames() {

        // Gets tablenames from the database
        $conn = $this->Connect();
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

    /************************************************************
        F03; D:connect(); S(G)
        Status: Good
        Function: returns columnnames out of the table specified
        Variables input:
            $tableName(expects a string of a DB tablename)
    */
    private function GetColumnNames($tableName) {

        //Gets column names from the database
        $conn = $this->Connect();
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

    /************************************************************
        F04; D:connect(); S(G)
        Status: Good
        Function: Extracts $_POST variables
        Variables input:
            $tableName(expects a string of a DB tablename)
    */
    public function ExtractPost($columnNames) {
        //extracts data from $_POST
        $extractedPost = [];
        for ($i=0; $i<count($columnNames); $i++) {
            if (isset($_POST[$columnNames[$i] ] ) ) {
                $extractedPost[$i] = $_POST[$columnNames[$i] ];
            }
        }
        return $extractedPost;
    }

    public function ExtractPostIDOnly() {
        $id = $_POST["id"];
        $extractedPost = "Where id = " . $id;

        return $extractedPost;
    }
}

class DB_Main extends DB_Primary {

    /**************************************************************************
        F05; D:connect(); S(G)
        Status: Good
        Function: Insert a record into an sql Table
        Variables input:
            $columnNames(needs a array of DB attribute names)
            The data you like to add needs to be inside $_POST['collumnnames']
            $tableName(needs a string of a DB tableName)
    */
    public function CreateDBRecord($tableName, $columnNames, $addArray) {

        //creates a connection with the Database
        $conn = $this->Connect();

        //Generates commaseperated names
        $commaSeperatedcolumnNames = $columnNames[0];
        for ($i=1; $i<count($columnNames); $i++) {
            $commaSeperatedcolumnNames .= "," . $columnNames[$i];
        }

        //Adds datafields till the last datafield is reached
        $article = "'" . $addArray[0] . "'";
        for ($i=1; $i<count($columnNames); $i++) {
            $article .= "," . "'" . $addArray[$i] . "'";
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

        $conn->close();
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
    public function ReadDBInto2DArray($tableName, $columnNames, $where) {
        //creates a connection with the Database
        $conn = $this->Connect();

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
            $conn->close();
            return $dataArray;
        }
        else {
            $conn->close();
        }
    }

    /*******************************************************************************
        F07; D:connect(), createWhere(); S(G)
        search words: Get Data Select data
        Status: Good
        Function: Returns an array of objects with strings from SQL database.
        Varables input:
            $tableName(expects an string with a DB tableName)
            $columnNames(expects a array of strings with a DB column names in them)
            $where(expects a string with a $where statement for the sql DB)
    */
    public function ReadDBIntoAssocArray($tableName, $columnNames, $where) {

        ///creates a connection with the Database
        $conn = $this->Connect();

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
            $conn->close();
            return $dataArray;
        }
        else {
            $conn->close();
        }

    }

    /********************************************************************************************
        F08; D:connect(); S(G)
        Status: Good
        FunctionDescription: Returns a array from a specified colom/attribute inside the database.
        Variable input:
            $tableName(expects an string with a sql table name)
            $columnName(expects an string with a sql column name)
    */
    function ReadSingleColumn($tableName, $columnName) {

        //creates a connection with the Database
        $conn = $this->Connect();

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

    /**********************************************************
        F09; D:connect(); S(999)
        Status: 999 not tested
        Function: Changes a value inside the set SQL database
        Variables input:
            $tableName(needs a string of a DB tableName)
            $set needs a prepared sql command as a string
            $where needs a prepared sql command as a string
    */
    public function UpdateDBRecord($tableName, $set, $where) {
        //creates a connection with the Database
        $conn = $this->Connect();

        $sql =
        "UPDATE $tableName
        SET $set
        $where";

        if ($conn->query($sql) === TRUE) {
            $sql = "Record Succesvol geupdate <br>";
        } else {
            $sql = "Record is niet geupdate " . $conn->error;
        }

        return $sql;
    }

    /********************************************************
        F10; D:connect(); S(999)
        Status: 999 not tested
        Function: Deletes a record inside the sql database
        Variables input:
            $tableName(needs a string of a DB tableName)
            $where needs a prepared sql command as a string
    */
    public function DeleteDBRecord($tableName, $where) {
        //creates a connection with the Database
        $conn = $this->Connect();

        $sql =
        "DELETE FROM $tableName
        $where";

        if ($conn->query($sql) === TRUE) {
            $sql = "Record Succesvol verwijdert <br>";
        } else {
            $sql = "Record is niet verwijdert " . $conn->error;
        }

        return $sql;
    }
}


class DB_Specify_Functions extends DB_Main {
    /*******************************************************************************************************
        F11; D:none; S(G)
        Status: Good
        FunctionDescription: With this function you can choose which column names you want inside an array
        Variables input:
            $columnNames(expects a array of strings with a DB column names in them)
            $code(expects a string with the numbers 0123 in them)
                this will be converting into a array and gets read out 1 by 1
                    0 means this data WILL NOT be used;
                    1 means this data WILL be used;
                    2 means this data and everything after it WILL be used;
                    3 means this data and everything after it WILL NOT be used;
    */
    public function SelectWithCodeFromArray($array, $code) {
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
        F12; D:none; S(G)
        Status: Good;
        FunctionDescription: generates the where statment from $_POST and given variable columnNames
        Varables input:
            $columnNames(expects a array of strings with a DB column names in them)
    */
    public function FullOpenWhere($columnNames, $array) {

        //Generates a where statement as long as the array $selectdata is long
        $whereState = "";
        for ($i=0; $i<count($array); $i++) {
            if (isset($array[$i]) ) {
                //if there is no data inside $selectdata then add nothing to the where statement.
                if ($array[$i] == "") {

                //else if there is Data inside $selectdata but no where statement yet then
                //(set the where statement and add the first condition)
                } else if ($array[$i] <> "" && $whereState == "") {
                    $whereState = " WHERE " . $this->OpenWhere($columnNames[$i], $array[$i]);

                //else if there is data and an already existing where statement
                } else {
                    $whereState .= " AND " . $this->OpenWhere($columnNames[$i], $array[$i]);
                }
            }
        }
        return $whereState;
    }

    /******************************************
        F13; D:connect(); S(999)
        Status: 999 not tested
        FunctionDescription:
            prepares a simple where statement
        Variables input:
            $whereKey expects a string
            $whereValue expects a string
    */
    public function OpenWhere($whereKey, $whereValue) {
        $whereState = $whereKey . ' LIKE "%' . $whereValue . '%"';
        return $whereState;
    }

    /******************************************
        F13; D:connect(); S(999)
        Status: 999 not tested
        FunctionDescription:
            prepares a simple where statement
        Variables input:
            $whereKey expects a string
            $whereValue expects a string
    */
    public function FullExactWhere($columnNames, $array) {

        //Generates a where statement as long as the array $selectdata is long
        $whereState = "";
        for ($i=0; $i<count($array); $i++) {
            if (isset($array[$i]) ) {
                //if there is no data inside $selectdata then add nothing to the where statement.
                if ($array[$i] == "") {

                //else if there is Data inside $selectdata but no where statement yet then
                //(set the where statement and add the first condition)
                } else if ($array[$i] <> "" && $whereState == "") {
                    $whereState = " WHERE " . $this->ExactWhere($whereKey, $whereValue);

                //else if there is data and an already existing where statement
                } else {
                    $whereState .= " AND " . $this->ExactWhere($whereKey, $whereValue);
                }
            }
        }
        return $whereState;
    }

    /******************************************
        F13; D:connect(); S(999)
        Status: 999 not tested
        FunctionDescription:
            prepares a simple where statement
        Variables input:
            $whereKey expects a string
            $whereValue expects a string
    */
    public function ExactWhere($whereKey, $whereValue) {
        $whereState = $whereKey . ' = "' . $whereValue . '"';
        return $whereState;
    }

    /***************************************
        F14; D:connect(); S(999)
        Status: 999 not tested
        FunctionDescription:
            creates a simple set statements
        Variables input:
            $setKeyColumn expects a string
            $setNewValue expects a string
    */
    public function updateSet($setKeyColumn, $setNewValue) {

        //creates the set statement
        $set = "$setKeyColumn = '$setNewValue'";
        return $set;
    }

}

class DB_Validation extends DB_Specify_Functions {
    /********************************************************************************
        F15; S(G)
        Status: Good
        Function: Test if a var is a number (handy when you want to test for a float)
        Variables input:
            $testVar can be a string or number
    */
    public function CheckIfNumeric($testVar) {
        if (is_numeric($testVar)) {
            return true;
        } else {
            return false;
        }
    }

    /****************************************************************************************
        F16; S(G)
        Status: Good
        Function: Test if a var is an integer number (handy when you want to test for a INT)
        Variables input:
            $testVar can be a string or number
    */
    public function CheckIfINT($testVar) {
        if (is_numeric($testVar) && floor($testVar) == $testVar) {
            return true;
        } else {
            return false;
        }
    }

    public function ValidateNotEmpty($columnNames, $ExtractedPost) {

        //tests if all fields are filled
        $test = "true";
        for ($i=0; $i<count($columnNames); $i++) {

            if (trim($ExtractedPost[$i], " ") == "") {
                $test = "false";
            }
        }

        //if tests where succesfull create sql query
        if ($test == 'true') {
            return true;

        //if not all fields are filled gives a popup that not all fields are filled
        } if ($test != 'true') {
            $message = "Fill in the whole form";
            echo "<script type='text/javascript'>alert('$message');</script>";
            return false;
        }
    }

    public function sanitizeSpecialChars($foulArray) {
        $res = [];
        for ($i=0; $i < count($foulArray); $i++) {
            $res[$i] = filter_var($foulArray[$i], FILTER_SANITIZE_STRING);
        }
        return $res;
    }
}



/***********************************************************
    F17; D:connect(); S(999)
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
    $conn = $newDbConnection->Connect();

    if (CheckIfINT($where) === false) {
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
