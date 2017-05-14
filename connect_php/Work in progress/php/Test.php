<?php
include 'database_connect.php';
//echo createTableFromDB1($tableNames[0], $collomNames[0]);
//echo createTableFromDB1($tableNames[1], $collomNames[1]);
echo addArticleForm($tableNames[1], $collomNames[1]);
echo addArticleForm($tableNames[0], $collomNames[0]);

echo '<form action="" method="POST">
    <input type="submit" name="select" value="Send" onclick="filltable()">';

echo '<select type="number" name="opdrachtnummer">';
$opdrachtNrs = getIndividualAtribute($tableNames[0], $collomNames[0][0]);
foreach ($opdrachtNrs as $oNr) {
    if ($oNr == 2) {
        echo '<option value="' . $oNr . '" selected>' . $oNr . '</option>';
    } else {
        echo '<option value="' . $oNr . '"> ' . $oNr . '</option>';
    }
}
echo '</select>
</form>';

echo '<table border="1" width="100%" height="200px">';
createTableFromDB3($tableNames, 8, 8);
echo '</table>';

?>
