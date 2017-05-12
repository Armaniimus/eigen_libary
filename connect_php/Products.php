<!DOCTYPE html>
<!--
version 1.0
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
        echo CreateTableFromDB2($tablename, $collomnames);
        echo '<br>Toon CreateTableFromDB2';
        echo CreateTableFromDB1($tablename, $collomnames);

        ?>
    </body>
</html>
