<?php
//include 'Test_single_elements/selectstatements.php';
include 'database_connect_currentproject.php';


insertIntoDatabase($collomNames[0], $tableNames[0]);
$wwwxs = selCollBinary($collomNames[0], '013');

echo createTableFromDB1($tableNames[0], $collomNames[0]);
echo createTableFromDB1($tableNames[0], $wwwxs);

?>
