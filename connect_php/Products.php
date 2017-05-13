<!DOCTYPE html>
<!--
version 1.1
-->
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <?php include('php/database_connect.php') ?>

    </head>
    <body>
        <?php
        echo 'Toon CreateTableFromDB1';
        echo createTableFromDB2($tablename, $collomnames);
        echo '<br>Toon CreateTableFromDB2';
        echo createTableFromDB1($tablename, $collomnames);
        echo '<br>Toon articleForm';
        echo addArticleForm($tablename, $collomnames);
        ?>

    </body>
</html>
