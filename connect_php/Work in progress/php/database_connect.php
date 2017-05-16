<?php
//-- version number 1.5 (inprogress) --//

//-- Start Instruction Manual --//
//
//      /////////////
//      Codes meaning
//      /////////////
//          1. code 999 are errors
//          2. code 555 is theoreticly correct but not tested
//          3. code 111 means start of new code not synced with git
//          4. code 222 means end of new code not synced with git
//          5. code 888 means check if its still valid
//
//      /////////////////////////
//      Available functionalities
//      /////////////////////////
//      form generators:
//
//          Table generators
//              createTableFromDB1(),
//              createTableFromDB2(),
//              createTableFromDB3();
//          Simpel form generator to add a record to a database:
//              addArticleForm();
//
//          Automatic query's
//              Automatic collomname query:
//                  getCollomNames();
//              Automatic attribute/collom querier
//                  getIndividualAtribute();
//
//          Add article functionality
//              insertIntoDatabase;
//-- end Instruction Manual --//
//-----------------------------------------------------------------------------//

//-- Start global variables                  --//
//-- Dependency connect(), getCollomNames(); --//
//---------------------------------------------/
$serverInfo = ["localhost", "root", "", "Project_over_de_rhein"];//Servername, Username, password, dbname
    //generates table information
    $tableNames = ['Opdrachten', 'Kabelchecklisten'];
    $collomNames = [];
    for ($i=0; $i<count($tableNames); $i++) {
        $collomNames[$i] = getCollomNames($tableNames[$i]);
    }

//-- Start functions --//
//---------------------//

//F01 D:none;
//Status: Good
//Function: Create connection with the specified database
function connect() {

    //insert the global variables inside this function
    global $serverInfo;

    //Create connection
    $conn = new mysqli($serverInfo[0], $serverInfo[1], $serverInfo[2], $serverInfo[3]);
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: ");
        // . $conn->connect_error);
    }

    return $conn;
}

//F02; D:connect();
//Status: Good
//Function: Get collomnames out of the table specified
function getCollomNames($tableName) {

    //gets collom names from the database
    $conn = connect();
    $sql = "SHOW COLUMNS FROM " . $tableName;
    $result = $conn->query($sql);

    //outputs data if information was found
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

//F03; D:connect();
//Status: Good
//Function: Insert Data into Database Table
function insertIntoDatabase($collomNames, $tableName) {

    if (isset($_POST["add"]) ) {
        $conn = connect();

        //extracts input data from superglobal $_POST exept collom1
        $addData = array();
        for ($i=1; $i<count($collomNames); $i++) {
            $addData[$i] = $_POST[$collomNames[$i]];
        }

        //tests if all fields are filled
        $test = "true";
        for ($i=1; $i<count($collomNames); $i++) {
            if ($addData[$i] == "") {
                $test = "false";
            }
        }

        //if tests where succesfull create sql query
        if ($test == 'true') {

            //Generates commaseperated names
            $commaSeperatedCollomNames = $collomNames[1];
            for ($i=2; $i<count($collomNames); $i++) {
                $commaSeperatedCollomNames .= "," . $collomNames[$i];
            }

            //Adds datafields till the last datafield is reached
            $article = "'" . $addData[1] . "'";
            for ($i=2; $i<count($collomNames); $i++) {
                $article .= "," . "'" . $addData[$i] . "'";
            }

            //Combines $article, $tableName and $commaSeperatedCollomNames to create the SQL query
            $sql = "INSERT INTO $tableName ($commaSeperatedCollomNames)
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

//F04; D:connect(), createWhere();
//Status: Good
//Function: Takes data from SQL database.
function selectFromDB($tableName, $collomNames) {

    //creates a connection with the Database
    $conn = connect();

    //Generates SELECT sql part
    $commaSeperatedCollomNames = $collomNames[0];
    for ($i=1; $i<count($collomNames); $i++) {
        $commaSeperatedCollomNames .= ", " . $collomNames[$i];
    }

    //Generates WHERE statement
    $where = createWhere($collomNames);

    //combines SELECT $tableName and WHERE parts to form sql query
    $sql = "SELECT $commaSeperatedCollomNames
    FROM $tableName
    $where";

    //saves query results
    $result = $conn->query($sql);

    $conn->close();
    return $result;
}

//F05; D:SelectFromDB();
//Status: Good
//Function: creates table from the provided data
function createTableFromDB1($tableName, $collomNames) {

    //gets data from database
    $result = selectFromDB($tableName, $collomNames);

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

//F06; D:selectFromDB();
//Status: Good
//Function: creates table from the provided data and adds buttons
function createTableFromDB2($tableName, $collomNames) {

    //gets data from database
    $result = selectFromDB($tableName, $collomNames);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {

        //opens table;
        $res = "<table border='1' width='100%'>";

        //generates the tableheads 1 by 1
        $tableHeads = "";
        for ($i=0; $i<count($collomNames); $i++) {
            $tableHeads .= "<th>" . $collomNames[$i] . "</th>";
        }
        $tableHeads .=  "<th colspan='3'>Buttons</th>";
        $res = $res . "<tr> $tableHeads </tr>";


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

//F07; D:none;
//Status: Good
//Function: generates an form from where you can add an article or search the database
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

//F08; D:connect();
//Status: no comments, false function description, Not tested, not working, not filled dependency (999)(111)
//FunctionDescription: generates a row with inputfields
function getIndividualAtribute($tableName, $collomName) {

    //Perform Query
    $conn = connect();
    $selectData =
    'SELECT ' . $collomName . ' AS "result"' .
    ' FROM ' . $tableName;
    $result = $conn->query($selectData);

    //if there are results then continue
    if ($result->num_rows > 0) {

        //sets $i and resAray variables
        $i = 0;
        $resArray = [];

        //writes names 1 by 1 into the variable $resArray
        while($row = $result->fetch_assoc()) {
            $resArray[$i] = $row['result'];
            $i++;
        }

        $conn->close();
        return $resArray;
    }
}

//F09; D:connect(), whereStatementSelectGen1();
//Status: no comments, false function description, Not tested, not working, not filled dependency (999)(111)
//Function: generates a table base
function fillTableFromDB($tableNames, $collomName, $width, $startNrRecord, $whereResult, $whereCollom) {

    //Perform Query
    $conn = connect();
    $selectData = whereStatementSelectGen1($tableNames, $startNrRecord, $whereCollom, $whereResult);
    $result = $conn->query($selectData);

    //if there are results then continue
    if ($result->num_rows > 0) {
        $i = 0;
        $resArray = [];

        //writes tablerows 1 by 1 into the variable $resArray
        while($row = $result->fetch_assoc()) {

            //sets the starting collomname to match the startingnumber
            $z = $startNrRecord;

            //Write the table row cells 1 by 1
            for ($y=0; $y<$width; $y++) {
                $resArray[$i][$y] = $row[$collomName[$z]];
                $z++;
            }
            $i++;
        }
        $conn->close();
        return $resArray;
    }
}

//F10; D:fillTableFromDB();
//Status: no comments, Not tested, not working, not filled dependency, (999)(111)
//Function: Generates a part of a table to save you work can be customize on height and width an can be automaticly filled
function createTableFromDB3($tableName, $colomName, $height, $width, $startNrRecord, $whereResult, $whereCollom, $runIf) {

    if (isset($_POST[$runIf]) ) {

        //gets datafrom sql            Tablename(s), collomname(s), length, startoftherecord, WhereResult, whereCollom
        $tableFiller = fillTableFromDB($tableName, $colomName, $width, $startNrRecord, $whereResult, $whereCollom);
    }

    //fills the table with data from array
    if (isset($tableFiller) ) {
        for ($x=0; $x<$height; $x++) {
            $res =  '<tr>';
                for ($y=0; $y<$width; $y++) {
                    if (isset($tableFiller[$x][$y]) ) {
                        $res .=  '<td>' . $tableFiller[$x][$y] . '</td>';
                    } else {
                        $res .=  '<td></td>';
                    }
                }
            $res .=  '</tr>';
        }
    } else {
        for ($x=0; $x<$height; $x++) {
            $res =  '<tr>';
            for ($y=0; $y<$width; $y++) {
                $res .=  '<td></td>';
            }
            $res .= '</tr>';
        }
    }
    return $res;
}

//F11; D:getCollomNames();
//Status: no comments, Not tested, not working, not filled dependency, (999)(111)
//FunctionDescription: Generates a part of a table to save you work can be customize on height and width an can be automaticly filled
function whereStatementSelectGen1($tableName, $startNrRecord, $whereCollom, $whereResult) {

    //generate collomnames
    $collomNames = getCollomNames($tableName);

    //generate select
    $x = 'SELECT ' . $collomNames[$startNrRecord];
    $startNrRecord + 1;
    for ($i=$startNrRecord; $i<count($collomNames); $i++) {
        $x = $x . ', ' . $collomNames[$i];
    }

    //Generates the where statement
    if ($whereResult == "" || $whereCollom == "") {
        $y = "";
    } else {
        $y = ' WHERE ' . $whereCollom . ' = ' . $whereResult;
    }

    //combine selectStatement From $x From($tableName), $y;
    $selectData =
    $x . ' FROM ' . $tableName . $y;

    return $selectData;
}

//F12; D:none;
//Status: no comments, Not tested, not filled dependency, (999)(111)
//FunctionDescription:
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

//----------------------//
//-- end of functions --//
//----------------------//
?>
