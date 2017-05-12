<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <?php include('php/database_connect.php') ?>

    </head>
    <body>
        <?php
        echo CreateTableFromDB2($tablename, $collomnames);
        echo CreateTableFromDB1($tablename, $collomnames);

        ?>
    </body>
</html>
