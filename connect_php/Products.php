<!DOCTYPE html>
<!--
version 1.2
-->
<html>
    <head>
        <meta charset="utf-8">
        <title>Database functions</title>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "stardunks";

        $tablename = 'products';
        include('php/database_connect.php');
        ?>

    </head>
    <body>
        <?php
        insertIntoDatabase($collomnames, $tablename);

        echo 'Toon CreateTableFromDB1';
        echo createTableFromDB2($tablename, $collomnames);
        echo '<br>Toon CreateTableFromDB2';
        echo createTableFromDB1($tablename, $collomnames);
        echo '<br>Toon articleForm';
        echo addArticleForm($tablename, $collomnames);
        ?>

    </body>
</html>
