<?php
//include 'Test_single_elements/selectstatements.php';
include 'database_connect.php';
//echo createTableFromDB1($tableNames[0], $collomNames[0]);
//echo createTableFromDB1($tableNames[1], $collomNames[1]);

//echo '<form action="" method="POST">
//    <input type="submit" name="select" value="Send">';
//echo '<select type="number" name="Opdrachtnummer">';
//$opdrachtNrs = getIndividualAtribute($tableNames[0], $collomNames[0][0]);

//foreach ($opdrachtNrs as $oNr) {
//    if ($oNr == 2) {
//        echo '<option value="' . $oNr . '" selected>' . $oNr . '</option>';
//    } else {
//        echo '<option value="' . $oNr . '"> ' . $oNr . '</option>';
//    }
//}
//echo '</select>
//</form>';

//echo '<table border="1" width="100%" height="200px">';

//echo addArticleForm($tableNames[1], $collomNames[1], 0 );
echo addArticleForm($tableNames[0], $collomNames[0], 0 );

//echo createTableFromDB2($tableNames[1], $collomNames[1]);
echo createTableFromDB2($tableNames[0], $collomNames[0]);
insertIntoDatabase($collomNames[0], $tableNames[0])


//echo '</table>';

//createTableFromDB3($tableNames, 1, 1);

?>
