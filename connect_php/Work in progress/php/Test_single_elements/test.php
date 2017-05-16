<?php
include('../database_connect.php');

echo createTableFromDB_1($tableNames[1], $collomNames[1]);
echo addArticleForm($tableNames[1], $collomNames[1], 0);
