<?php

//-------------------------------------//
//-- version number 1.4 (inprogress) --//
//-------------------------------------//

//------------------------------//
//-- Start Instruction Manual --//
//------------------------------//

//////////////////////////////////////////////////////////////////
//Codes meaning
//1. code 999 are errors
//2. code 555 is theoreticly correct but not tested
//3. code 111 means start of new code not synced with git
//4. code 222 means end of new code not synced with git
///////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////
//Available functionalities
//
//form generators:
//  Table generators:
//    createTableFromDB1(),
//    createTableFromDB2(),
//    createTableFromDB3();
//  Simpel form generator to add a record to a database:
//    addArticleForm();
//
//Automatic query's
//  Automatic collomname query:
//    getcollomnames()
//  Automatic attribute/collom querier
//    getIndividualAtribute()
//
///////////////////////////////////////////////////





//----------------------------//
//-- End Instruction manuel --//
//----------------------------//

//---------------------------------------------//
//-- Start global variables                  --//
//-- Dependency connect(), getcollomnames(); --//
//---------------------------------------------//

//(111)
//Server information
// ==> Servername, Username, password, dbname <==
$serverInfo = ["localhost", "root", "", "Project_over_de_rhein"];

//generates table information
$tableNames = ['Opdrachten', 'Kabelchecklisten'];
$collomNames = [];

for ($i=0; $i<count($tableNames); $i++) {
    $collomNames[$i] = getcollomnames($tableNames[$i]);
}

//-----------------------------//
//-- end of global variables --//
//-----------------------------//

//---------------------//
//-- Start functions --//
//---------------------//

///////////////////////////////////////////////////////////
//FunctionNr: 01
//Status: Good
//Function: Create connection with the specified database
//Dependency none;
///////////////////////////////////////////////////////////
function createConnection() {

    //insert the global variables inside this function
    global $serverInfo;

    //Create connection
    $conn = new mysqli($serverInfo[0], $serverInfo[1], $serverInfo[2], $serverInfo[3]);
//(222)
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: ");
        // . $conn->connect_error);
    }

    return $conn;
}

/////////////////////////////////////////////////////////
//FunctionNr: 02
//Status: Good
//Function: Get collomnames out of the table specified
//Dependency: connect();
/////////////////////////////////////////////////////////
function getcollomnames($tablename) {

    //gets collom names from the database
    $conn = createConnection();
    $sql = "SHOW COLUMNS FROM " . $tablename;
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

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 03
//Status: Good
//Function: Insert Data into Database Table
//Dependency: extractfrompost(), commaseperatedcollomnames(), createConnection(), addDataCheck(), addDataNormal();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function insertIntoDatabase($collomnames, $tablename) {

    if (isset($_POST["add"]) ) {
        $conn = createConnection();

        //extracts input data from superglobal $_POST exept id
        $addData = extractfrompost(1, $collomnames);

        //if all the fields are filled
        if (addDataCheck($collomnames, $addData) ) {

            //creates SQL query
            $sql = "INSERT INTO " . $tablename . "(" . commaseperatedcollomnames($collomnames,2) . ")
            VALUES (" . addDataNormal($collomnames, $addData) . ")";

            //if acticle whas added successfully
            if ($conn->query($sql) === TRUE) {

                //Creates a popup for the user
                $message = "New record created successfully";
                echo "<script type='text/javascript'>alert('$message');</script>";

                //refreshes page
                echo "<script type='text/javascript'>('window.location.reload();')</script>";
            }

        //if not all fields are filled
        } else {
            //creates popup
            $message = "Fill in the whole form";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }

        //closes connection
        $conn->close();
    }
}

///////////////////////////////////////////////////////////////////////////
//FunctionNr: 04
//Status: Good
//Function: Takes data from SQL database.
//Dependency: connect(), createSearchQuery(), commaseperatedcollomnames();
///////////////////////////////////////////////////////////////////////////
function SelectFromDB($collomnames, $tablename) {

    //creates a connection with the Database
    $conn = createConnection();

    //creates the where statement
    $querysearch = createSearchQuery($collomnames);

    $sql = "SELECT " . commaseperatedcollomnames($collomnames,1) . " FROM " . $tablename;

    //combines querysearch if it's defined
    if ($querysearch != "") {
        $sql = $sql . $querysearch;
    } else {
        $sql = $sql;
    }

    //sends query to the database
    $result = $conn->query($sql);

    //closes connection
    $conn->close();

    //returns result
    return $result;
}

////////////////////////////////////////////////////////////////////////
//FunctionNr: 05
//Status: Good
//Function: creates table from the provided data
//Dependency: SelectFromDB(), tablemainrow(), tableHead();
////////////////////////////////////////////////////////////////////////
function createTableFromDB1($tablename, $collomnames) {

    //gets data from database
    $result = SelectFromDB($collomnames, $tablename);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {
        //opens table;
        $res = "<table border='1' width='100%'>";

        //generates the collomheads 1 by 1
        $res = $res . "<tr>" . tableHead($collomnames, 1) . "</tr>";

        //generates the tablerows 1 by 1;
        while($row = $result->fetch_assoc()) {
            $res = $res .
            "<tr>" . tablemainrow($row, $collomnames) .
            "</tr>";
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

////////////////////////////////////////////////////////////////////////
//FunctionNr: 06
//Status: Good
//Function: creates table from the provided data and adds buttons
//Dependency: SelectFromDB(), tableHead(), tablemainrowactions();
////////////////////////////////////////////////////////////////////////
function createTableFromDB2($tablename, $collomnames) {

    //gets data from database
    $result = SelectFromDB($collomnames, $tablename);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {

        //opens table;
        $res = "<table border='1' width='100%'>";
        $res = $res . "<tr>" . tableheadactions($collomnames) . "</tr>";

        //generates the tablerows 1 by 1;
        while($row = $result->fetch_assoc()) {
            $res = $res .
            "<tr>" . tablemainrowactions($row, $collomnames) .
            "</tr>";
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

///////////////////////////////////////////////////////
//FunctionNr: 07
//Status: Good
//Function: extracts data from the superglobal $_POST
//Dependency: none;
///////////////////////////////////////////////////////
function extractfrompost($y, $collomnames) {

    if ($y == 1) {
        $z = array();

        //Fills the $z array with the data from $_POST
        for ($i=1; $i<count($collomnames); $i++) {
            $z[$i] = $_POST[$collomnames[$i]];
        }
        return $z;

    }
    if ($y == 2) {
        $z = array();

        //Fills the $z array with the data from $_POST
        for ($i=0; $i<count($collomnames); $i++) {
            $z[$i] = $_POST[$collomnames[$i]];
        }
        return $z;
    }
}

///////////////////////////////////////////
//FunctionNr: 08
//Status: Good
//Function: Creates searchquery
//Dependency: extractfrompost(), tests_POST()
///////////////////////////////////////////
function createSearchQuery($collomNames) {

    //tests id the required _Post[] var exists
    if (testColomsSuperGlobalPost($collomNames) == 'passed') {

        //extracts inputted where statements from superglobal $_POST
        //from all colloms in the specified HTMLtable
        $selectdata = extractfrompost(2, $collomNames);

        $whereState = "";

        //generates a where statement as long as the array $selectdata is long
        for ($i=0; $i<count($selectdata); $i++) {

            //if there is no data inside $selectdata the add nothing to the where statent.
            if ($selectdata[$i] == "" || $selectdata[$i] == '"%' . '%"') {

            //if there is Data inside $selectdata but no where statement yet then
            //  (set the where statement and add the first condition)
            } else if ($whereState == "") {

                //Sets the where condition and adds the first condition.
                $whereState = " WHERE " . $collomNames[$i] . ' LIKE "%' . $selectdata[$i] . '%"';

            //if there is Data inside $selectdata and a where statement then
            //  (add a condition to the where statement)
            } else {

                //adds a condition to the where statement.
                $whereState = $whereState . " AND " . $collomNames[$i] . ' LIKE "%' . $selectdata[$i] . '%"';
            }
        }
        return $whereState;
    }
}


////////////////////////////////////////////
//FunctionNr: 09
//Status: Good
//Function: table convert functions
//Dependency: none;
///////////////////////////////////////////
function commaseperatedcollomnames($collomNames, $nr) {

    //generates commaseperatedcollomnames with id included
    if ($nr == 1) {
        $y = $collomNames[0];
        for ($i=1; $i<count($collomNames); $i++) {
            $y = $y . "," . $collomNames[$i];
        }
    }

    //generates comma seperated collom names with id Excluded
    if ($nr == 2) {
        $y = $collomNames[1];
        for ($i=2; $i<count($collomNames); $i++) {
            $y = $y . "," . $collomNames[$i];
        }
    }
    return $y;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 10
//Status: Good
//Function: Creates the datafields for the insert sql query but skips the id because that is automaticly generated by sql.
//Dependency: none
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function addDataNormal($collomNames, $addData) {
    //must begin with 1 because it needs to skip id

    //sets y variable with the first data field
    $y = "'" . $addData[1] . "'";

    //adds datafields till the last datafield is reached
    for ($i=2; $i<count($collomNames); $i++) {
        $y = $y . "," . "'" . $addData[$i] . "'";
    }

    return $y;
}

/////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 11
//Status: Good
//Function: generates tests to check if all fields from the given array are filled
//Dependency: none
/////////////////////////////////////////////////////////////////////////////////////
function addDataCheck($collomnames, $addData) {
    //must begin with 1 because it needs to skip id

    $y = "";

    //generates multible tests
    for ($i=1; $i < count($collomnames); $i++) {

        //adds a test to check if a field is filled
        $y = $y . "&&" . $addData[$i] . "<>" . '"'. '"' ;
    }
    return $y;
}

/////////////////////////////////////////////////////////////
//FunctionNr: 12
//Status: Good
//Function: Generates table rows with provided information;
//Dependency: none
/////////////////////////////////////////////////////////////
function tablemainrow($row, $collomnames) {
    $y = "";

    //generates a table row with data
    for ($i=0; $i<count($collomnames) ; $i++) {

        //adds a field inside the current row
        $y = $y . "<td>" . $row[$collomnames[$i]] . "</td>";
    }
    return $y;
}

///////////////////////////////////////////////////////////////////////
//FunctionNr: 13
//Status: good
//Function: Takes the generated row and adds buttons to it
//Dependency: tablemainrow();
///////////////////////////////////////////////////////////////////////
function tablemainrowactions($row, $collomnames) {

    //creates the info part the row
    $y = tablemainrow($row, $collomnames);

    //adds 3 buttons to this row
    $y = $y .
    "<td><button type='submit' form='form1' value='read'>Read</button></td>
    <td><button type='submit' form='form1' value='update'>Update</button></td>
    <td><button type='submit' form='form1' value='delete'>Delete</button></td>";

    return $y;
}

///////////////////////////////////////////////////////////////////////
//FunctionNr: 14
//Status: Good
//Function: Generates table collomheads with the provided information
//Dependency: none;
///////////////////////////////////////////////////////////////////////
function tableHead($collomNames, $y) {
    $res = "";

    //generates tableheads with id
    if ($y == 1) {

        //creates the top row of the table with collomnames
        for ($i=0; $i<count($collomNames); $i++) {
            $res = $res . "<th>" . $collomNames[$i] . "</th>";
        }
    }

    //generates tableheads without id
    if ($y == 2) {

        //creates the top row of the table with collomnames
        for ($i=1; $i<count($collomNames); $i++) {
            $res = $res . "<th>" . $collomNames[$i] . "</th>";
        }
    }
    return $res;
}

///////////////////////////////////////////////////////////////////////
//FunctionNr: 15
//Status: Good
//Function: Takes the collom heads and adds th collomhead Actions
//Dependency: tableHead();
///////////////////////////////////////////////////////////////////////
function tableheadactions($collomNames) {

    //generates the data part for the top table row
    $y = tableHead($collomNames, 1);

    //adds the button header
    $y = $y . "<th colspan='3'>Actions</th>";

    return $y;
}
///////////////////////////////////////////////////////////////////////
//FunctionNr: 16
//Status: Good
//Function: Tests if there is data inside superglobal $_POST
//Dependency: none
///////////////////////////////////////////////////////////////////////
function tests_POST($collomNames) {

    // tests if the colloms exist in the superglobal $_POST
    for ($i=0; $i<count($collomNames); $i++) {

        //tests a
        if (isset($_POST[$collomnames[$i] ]) ){

        } else {
            return 'FAILED';
        }
    }
    return 'passed';
}
//////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 17
//Status: Good
//Function: generates an form From wher you can add an into the database
//Dependency: tableHead(), addFormMain();
//////////////////////////////////////////////////////////////////////////////////////////
function addArticleForm($tablename, $collomnames) {

    //opens form and table
    $res =
        '<form name="dataConfig" action="" method="POST">
        <table border="1" width="100%"  overflow-x="auto">';

    //generate table collomheads
    $res = $res . '<tr>' . tableHead($collomnames, 2) . '</tr>';

    //Generates the inputfields
    $res = $res . addFormMain($collomnames);

    //close the table, add the add button and close the form
    $res = $res .
        '</table>
        <input name="add" type="submit" value="Add">
        </form>';

    return $res;
}
///////////////////////////////////////////////////////////////////////
//FunctionNr: 18
//Status: Good
//Function: generates a row with inputfields
//Dependency: none
///////////////////////////////////////////////////////////////////////
function addFormMain($collomnames){

    //opens row
    $res = '<tr>';

    //generates a table row with data
    for ($i=1; $i<count($collomnames); $i++) {

        //adds a inputfield
        $res = $res . '<td>' . '<input name="' . $collomnames[$i] . '" type="text"> </td>';
    }

    //close row
    $res = $res . '</tr>';

    return $res;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 19
//Status: no comments, false function description, Not tested, not working, not filled dependency (999)(111)
//Function: generates a row with inputfields
//Dependency: createConnection(),
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getIndividualAtribute($tableName, $collomName) {

    //Perform Query
    $conn = createConnection();
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
//(222)

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 20
//Status: no comments, false function description, Not tested, not working, not filled dependency (999)(111)
//Function: generates a table base
//Dependency: createConnection(), whereStatementSelectGen1();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function fillTableFromDB($tableNames, $collomName, $width, $startNrRecord, $whereResult, $whereCollom) {

    //Perform Query
    $conn = createConnection();
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
//(222)

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 20
//Status: no comments, Not tested, not working, not filled dependency, (999)(111)
//Function: Generates a part of a table to save you work can be customize on height and width an can be automaticly filled
//Dependency:fillTableFromDB();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function createTableFromDB3($tableName, $colomName, $height, $width, $startNrRecord, $whereResult, $whereCollom, $runIf) {

    if (isset($_POST[$runIf]) ) {

        //gets datafrom sql            Tablename(s), collomname(s), length, startoftherecord, WhereResult
        $tableFiller = fillTableFromDB($tableName, $colomName, $width, $startNrRecord, $whereResult, $whereCollom);
    }

    //fills the table with data from array
    if (isset($tableFiller) ) {
        for ($x=0; $x<$height; $x++) {
            echo '<tr>';
                for ($y=0; $y<$width; $y++) {
                    if (isset($tableFiller[$x][$y]) ) {
                        echo '<td>' . $tableFiller[$x][$y] . '</td>';
                    } else {
                        echo '<td></td>';
                    }
                }
            echo '</tr>';
        }
    } else {
        for ($x=0; $x<$height; $x++) {
            echo '<tr>';
            for ($y=0; $y<$width; $y++) {
                echo '<td></td>';
            }
            echo'</tr>';
        }
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 21
//Status: no comments, Not tested, not working, not filled dependency, (999)(111)
//Function: Generates a part of a table to save you work can be customize on height and width an can be automaticly filled
//Dependency: none
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function whereStatementSelectGen1($tableName, $startNrRecord, $whereCollom, $whereResult) {

    //generate collomnames
    $collomNames = getcollomnames($tableName);

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
//(222)

//----------------------//
//-- end of functions --//
//----------------------//
?>
