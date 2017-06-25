<?php
/////////////////////////////////////////////////////
//version number 1.3
//
//Short instruction
//1. code 999 are errors
//2. code 555 is theoreticly correct but not tested
/////////////////////////////////////////////////////


//////////////////////////////////////////////
//FunctionNr: 00
//Status: Good
//Function: global variables
//Dependency connect(), getcollomnames();
///////////////////////////////////////////////

//Server information
//$servername = "localhost";
//$username = "root";
//$password = "";
//$dbname = "stardunks";

//Table information
//$tablename = 'products';
$collomnames = getcollomnames($tablename);

///////////////////////////////////////////////////////////
//FunctionNr: 01
//Status: Good
//Function: Create connection with the specified database
//Dependency none;
///////////////////////////////////////////////////////////
function createConnection() {

    //insert the global variables inside this function
    global $servername, $username, $password, $dbname;

    //Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    //echo  '<br>' . $servername . '<br>' . $username .  '<br>' . $password .  '<br>' . $dbname;

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: ");
        // . $conn->connect_error);
    }

    //send back connection
    //var_dump($conn);
    return $conn;
}

/////////////////////////////////////////////////////////
//FunctionNr: 02
//Status: Good
//Function: Get collomnames out of the table specified
//Dependency: connect();
/////////////////////////////////////////////////////////
function getcollomnames($tablename) {

    //sets $colArray variable
    $colArray = array();

    //gets the database connect information
    $conn = createConnection();

    //sets the query for the database
    $sql = "SHOW COLUMNS FROM " . $tablename;

    //gets collom names from the database
    $result = $conn->query($sql);

    //outputs data if information was found
    if ($result->num_rows > 0) {

        //sets $i variable
        $i = 0;

        //writes names 1 by 1 into the variable $colarray
        while($row = $result->fetch_assoc()) {
            $colArray[$i] = $row['Field'];

            //increments $i with 1
            $i++;
        }

        //closes connection
        $conn->close();

        //returns the $collArray variable
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

    // if the variable $_POST['add'] exists
    if (isset($_POST["add"]) ) {

        //creates a connection with the Database
        $conn = createConnection(); //creating a connection with database

        //stops script and echo's error message when there is a error
        if ($conn->connect_error) {
            die("Connection failed: ");
            // . $conn->connect_error);
        }

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
    if ($conn->connect_error) {
        die("Connection failed:");
    }

    //creates the WHERE statement
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
        echo $res;
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
        echo $res;
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

    //Defines $z variable
    $z = "";

    //
    if ($y == 1) {

        //Creates array
        $z = array();

        //Fills the $z array with the data from $_POST
        for ($i=1; $i<count($collomnames); $i++) {
            $z[$i] = $_POST[$collomnames[$i]];
        }
    }
    if ($y == 2) {

        //Creates array
        $z = array();

        //Fills the $z array with the data from $_POST
        for ($i=0; $i<count($collomnames); $i++) {
            $z[$i] = $_POST[$collomnames[$i]];
        }
    }
    return $z;
}

///////////////////////////////////////////
//FunctionNr: 08
//Status: Good
//Function: Creates searchquery
//Dependency: extractfrompost(), testColomsSuperGlobalPost()
///////////////////////////////////////////
function createSearchQuery($collomnames) {

    //extracts inputted where statements from superglobal $_POST
    //from all colloms in the specified table

    if (testColomsSuperGlobalPost($collomnames) == 'passed') {
        $selectdata = extractfrompost(2, $collomnames);

        //sets the where statement
        $whereState = "";

        //generates a where statement as long as the array $selectdata is long
        for ($i=0; $i<count($selectdata); $i++) {

            //if there is no data inside $selectdata the add nothing to the where statent.
            if ($selectdata[$i] == "" || $selectdata[$i] == '"%' . '%"') {

            //if there is Data inside $selectdata but no where statement yet then
            //  (set the where statement and add the first condition)
            } else if ($whereState == "") {

                //Sets the where condition and adds the first condition.
                $whereState = " WHERE " . $collomnames[$i] . ' LIKE "%' . $selectdata[$i] . '%"';

            //if there is Data inside $selectdata and a where statement then
            //  (add a condition to the where statement)
            } else {

                //adds a condition to the where statement.
                $whereState = $whereState . " AND " . $collomnames[$i] . ' LIKE "%' . $selectdata[$i] . '%"';
            }
        }

        //$returns the $whereState variable
        return $whereState;
    }
}


////////////////////////////////////////////
//FunctionNr: 09
//Status: Good
//Function: table convert functions
//Dependency: none;
///////////////////////////////////////////
function commaseperatedcollomnames($collomnames, $nr) {

    //generates comma seperated collom names with id included
    if ($nr == 1) {

        //sets $y on the id
        $y = $collomnames[0];

        //adds the collom names 1 by 1
        for ($i=1; $i<count($collomnames); $i++) {

            //adds a comma and a collomname
            $y = $y . "," . $collomnames[$i];
        }
    }

    //generates comma seperated collom names with id Excluded
    if ($nr == 2) {

        //sets $y after the id
        $y = $collomnames[1];

        //adds the collom names 1 by 1
        for ($i=2; $i<count($collomnames); $i++) {

            //adds a comma and a collomname
            $y = $y . "," . $collomnames[$i];
        }

    }

    //returns $y variable
    return $y;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FunctionNr: 10
//Status: Good
//Function: Creates the datafields for the insert sql query but skips the id because that is automaticly generated by sql.
//Dependency: none
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function addDataNormal($collomnames, $addData) {
    //must begin with 1 because it needs to skip id

    //sets y variable with the first data field
    $y = "'" . $addData[1] . "'";

    //adds datafields till the last datafield is reached
    for ($i=2; $i<count($collomnames); $i++) {
        $y = $y . "," . "'" . $addData[$i] . "'";
    }

    //returns the $y variable
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

    //sets $y variable
    $y = "";

    //generates multible tests
    for ($i=1; $i < count($collomnames); $i++) {

        //adds a test to check if a field is filled
        $y = $y . "&&" . $addData[$i] . "<>" . '"'. '"' ;
    }

    //returns $y variable
    return $y;
}

/////////////////////////////////////////////////////////////
//FunctionNr: 12
//Status: Good
//Function: Generates table rows with provided information;
//Dependency: none
/////////////////////////////////////////////////////////////
function tablemainrow($row, $collomnames) {

    //sets the $y variable
    $y = "";

    //generates a table row with data
    for ($i=0; $i<count($collomnames) ; $i++) {

        //adds a field inside the current row
        $y = $y . "<td>" . $row[$collomnames[$i]] . "</td>";
    }

    //returns the $y variable
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
    $y = $y .   "<td><button type='submit' form='form1' value='read'>Read</button></td>
                <td><button type='submit' form='form1' value='update'>Update</button></td>
                <td><button type='submit' form='form1' value='delete'>Delete</button></td>";

    //gives the variable $y back
    return $y;
}

///////////////////////////////////////////////////////////////////////
//FunctionNr: 14
//Status: Good
//Function: Generates table collomheads with the provided information
//Dependency: none;
///////////////////////////////////////////////////////////////////////
function tableHead($collomnames, $y) {

    //sets the $y variable
    $res = "";

    //generates tableheads with id
    if ($y == 1) {
        //creates the top row of the table with collomnames
        for ($i=0; $i<count($collomnames); $i++) {
            $res = $res . "<th>" . $collomnames[$i] . "</th>";
        }
    }

    //generates tableheads without id
    if ($y == 2) {
        //creates the top row of the table with collomnames
        for ($i=1; $i<count($collomnames); $i++) {
            $res = $res . "<th>" . $collomnames[$i] . "</th>";
        }
    }

    //returns the $y variable
    return $res;
}

///////////////////////////////////////////////////////////////////////
//FunctionNr: 15
//Status: Good
//Function: Takes the collom heads and adds th collomhead Actions
//Dependency: tableHead();
///////////////////////////////////////////////////////////////////////
function tableheadactions($collomnames) {

    //generates the data part for the top table row
    $y = tableHead($collomnames, 1);

    //adds the button header
    $y = $y . "<th colspan='3'>Actions</th>";

    //returns the $y variable
    return $y;
}
///////////////////////////////////////////////////////////////////////
//FunctionNr: 16
//Status: Good
//Function: Tests if there is data inside superglobal $_POST
//Dependency: none
///////////////////////////////////////////////////////////////////////
function testColomsSuperGlobalPost($collomnames) {

    // tests if the colloms exist in the superglobal $_POST
    for ($i=0; $i<count($collomnames); $i++) {

        //tests a
        if (isset($_POST[$collomnames[$i] ]) ){

        } else{
            return 'FAILED';
        }
    }

    //returns $y variable
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

    //return the table
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

    //return $res variable
    return $res;
}
?>
