<?php
$serverInfo = ["localhost", "root", "", "Project_over_de_rhein"];

$tableNames = ['Opdrachten', 'Kabelchecklisten'];
$collomNames = [];

for ($i=0; $i<count($tableNames); $i++) {
    $collomNames[$i] = getCollomNames($tableNames[$i]);
}

function connect() {

    //insert the global variables inside this function
    global $serverInfo;

    //Create connection
    $conn = new mysqli($serverInfo[0], $serverInfo[1], $serverInfo[2], $serverInfo[3]);

    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: ");
    }

    return $conn;
}

function getCollomNames($tablename) {

    //gets collom names from the database
    $conn = connect();
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
