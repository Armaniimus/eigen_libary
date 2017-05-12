<?php
///////////////////////////////
//version number 1.0;
//////////////////////////////

//////////////////////////////////////////////
//
//function: global variables
//Dependency connect(), getcollomnames();
//
///////////////////////////////////////////////
$tablename = 'products';
$collomnames = getcollomnames($tablename);


///////////////////////////////////////////////////////////
//
//Function: Create connection with the specified database
//Dependency none;
//
///////////////////////////////////////////////////////////
function createConnection() {

    //defines variables
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "stardunks";

    //Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: ");
        // . $conn->connect_error);
    }

    //send back connection
    return $conn;
}
/////////////////////////////////////////////////////////
//
//Function: Get collomnames out of the table specified
//Dependency: connect();
//
/////////////////////////////////////////////////////////
function getcollomnames($tablename) {
    $colArray = array();

    $conn = createConnection();
    $sql= "SHOW COLUMNS FROM " . $tablename;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        $i = 0;
        while($row = $result->fetch_assoc()) {
            $colArray[$i] = $row['Field'];
            $i++;
        }
        return $colArray;
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////
//
//Function: Insert Data into Database Table;
//Dependency: postd(), commaSeperatedHT(), createConnection(), addDataCheck(), addDataNormal();
//
/////////////////////////////////////////////////////////////////////////////////////////////////
function insertIntoDatabase($collomnames, $tablename) {

    // if the variable $_POST['add'] exists
    if (isset($_POST["add"]) ) {

        //creates a connection with the Database
        $conn = createConnection(); //creating a connection with database
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //extracts data from superglobal $_POST
        $addData = postd(1, $collomnames);

        //if all the fields are filled
        if (addDataCheck($collomnames, $addData) ) {

            //creates SQL query
            $sql = "INSERT INTO " . $tablename . "(" . commaSeperatedHT($collomnames,2) . ")
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
            $message = "Fill in the whole form";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }

        //closes connection
        $conn->close();
    }
}

///////////////////////////////////////////////////////////////////////////
//
//Function: Takes data from SQL database.
//Dependency: connect(), createSearchQuery(), commaSeperatedHT();
//
///////////////////////////////////////////////////////////////////////////
function SelectFromDB($collomnames, $tablename) {

    //creates a connection with the Database
    $conn = createConnection();
    if ($conn->connect_error) {
        die("Connection failed:");
    }

    //creates the WHERE statement
    $querysearch = createSearchQuery($collomnames);

    $sql = "SELECT " . commaSeperatedHT($collomnames,1) . " FROM " . $tablename;

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
//
//Function: creates table from the provided data
//Dependency: SelectFromDB(), tablemainrow(), tableheadactions();
//
////////////////////////////////////////////////////////////////////////
function CreateTableFromDB1($tablename, $collomnames) {

    //gets data from database
    $result = SelectFromDB($collomnames, $tablename);

    //starts generatign table if there is available data;
    if ($result->num_rows > 0) {
        //opens table;
        $res = "<table border='1' width='100%'>";

        //generates the collomheads 1 by 1
        $res = $res . "<tr>" . tablehead($collomnames) . "</tr>";

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
//
//Function: creates table from the provided data and adds buttons
//Dependency: SelectFromDB(), tablehead(), tablemainrowactions();
//
////////////////////////////////////////////////////////////////////////
function CreateTableFromDB2($tablename, $collomnames) {

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

//////////////////////////////////////////////////
//
//Function: extracts data from the superglobal $_POST
//Dependency: none;
//
//////////////////////////////////////////////////
function postd($y, $collomnames) {
    //Defines $z variable
    $z = "";

    if ($y == 1) {

        //Creates array
        $z = array();

        //Fills the $z array with the data from $_POST
        for ($i=1; $i<count($collomnames); $i++) {
            $z[$i] = $_POST[$collomnames[$i]];
        }
    }
    return $z;
}

///////////////////////////////////////////
//
//Function: Creates searchquery
//Dependency: postd()
//
///////////////////////////////////////////
function createSearchQuery($collomnames) {

    //This part is broken;
    $addData = postd(2, $collomnames);
    $whereState = "";

    if ($addData == null || $addData == 0 || $addData == "") {
        echo $whereState . "";
        return $whereState;
    }

    for ($i=0; $i < count($addData); $i++) {
        if ($addData[$i] == "" || $addData[$i] == '"%' . '%"') {
            //echo "p $i";
            //echo "<br>check --> " . '"' . '"' . " <-- check <br>" ;
        } else if ($whereState == "") {
            //echo "w $i" . $addData[$i];
            $whereState = " WHERE " . $collomnames[$i] . ' LIKE "%' . $addData[$i] . '%"';
        } else {
            $whereState = $whereState . " AND " . $collomnames[$i] . ' LIKE "%' . $addData[$i] . '%"';
        }
    }
    return $whereState;
}


////////////////////////////////////////////
//
//Function: table convert functions
//Dependency: none;
//
///////////////////////////////////////////
function commaSeperatedHT($collomnames, $nr) {
    if ($nr == 1) {
        $y = $collomnames[0];

        for ($i=1; $i < count($collomnames) ; $i++) {
            $y = $y . "," . $collomnames[$i];
        }
    }
    if ($nr == 2) {
        $y = $collomnames[1];

        for ($i=2; $i < count($collomnames) ; $i++) {
            $y = $y . "," . $collomnames[$i];
        }

    }
    return $y;
}

function addDataNormal($collomnames, $addData) {
    $y = "'" . $addData[1] . "'";
    for ($i=2; $i < count($collomnames); $i++) {
        $y = $y . "," . "'" . $addData[$i] . "'";
    }
    return $y;
}

//////////////////////////////////////
//
//Function:
//Dependency:
//
//////////////////////////////////////
function addDataCheck($collomnames,$addData) {
    $y = "";
    for ($i=1; $i < count($collomnames); $i++) {
        $y = $y . "&&" . $addData[$i] . "<>" . '"'. '"' ;
    }
    return $y;
}

////////////////////////////////////////////
//
//Function: Generates table rows with provided information;
//Dependency: none;
//
///////////////////////////////////////////
function tablemainrow($row, $collomnames) {
    $y = "";
    for ($i=0; $i < count($collomnames) ; $i++) {
        $y = $y . "<td>" . $row[$collomnames[$i]] . "</td>";
    }
    return $y;
}

///////////////////////////////////////////////////////////////////////
//
//Function: Takes the generated row and adds function buttons to it
//Dependency: tablemainrow();
//
///////////////////////////////////////////////////////////////////////
function tablemainrowactions($row, $collomnames) {
    $y = tablemainrow($row, $collomnames);

    //extra buttons
    $y = $y .   "<td><button type='submit' form='form1' value='read'>Read</button></td>
                <td><button type='submit' form='form1' value='update'>Update</button></td>
                <td><button type='submit' form='form1' value='delete'>Delete</button></td>";
    return $y;
}

///////////////////////////////////////////////////////////////////////
//
//Function: Generates table collomheads with the provided information
//Dependency: none;
//
///////////////////////////////////////////////////////////////////////
function tablehead($collomnames) {
    $y = "";
    for ($i=0; $i < count($collomnames) ; $i++) {
        $y = $y . "<th>" . $collomnames[$i] . "</th>";
    }
    return $y;
}

///////////////////////////////////////////////////////////////////////
//
//Function: Takes the collom heads and adds th collomhead Actions
//Dependency: tablehead();
//
///////////////////////////////////////////////////////////////////////
function tableheadactions($collomnames) {
    $y = tablehead($collomnames);
    $y = $y . "<th colspan='3'>Actions</th>";
    return $y;
}

?>
