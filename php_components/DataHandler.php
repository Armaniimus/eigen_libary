<?php

/**
 * @summary This class is used to contain the neccesary crud functions to interact with database records
 * @property object $pdo  a variable to store the connection
 * @method create() create a database record
 * @method read()   read a database record
 * @method update() update a database record
 * @method delete() delete a database record
*/
class DataHandler {
    private static $db;
    private static $conn;


    /**
     * Connect for the datahandler
     *
     * @param string $server        Database server adress
     * @param string $database      database name for the connection
     * @param string $username      database username for the connection
     * @param string $password      database password for the connection
     */
    public static function setConn($server, $db, $user, $pass) {
        try {
            self::$db = $db;
            self::$conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            // set the PDO error mode to exception
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";

        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    private static function crudFunc($sql, $bindings) {
        $query = self::$conn->prepare($sql);
        $query->execute($bindings);
        return $query;
    }

    /**
     * read data in the database
     *
     * @param  string   $sql the sql query
     * @param  array    $bindings
     * @param  bool     $multible
     * @return array
     */
    public static function read(string $sql, array $bindings = [], $multible = TRUE) {
        $query = self::crudFunc($sql, $bindings);

        if ($multible) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    /**
     * insert data in the database
     *
     * @param  string   $sql the sql query
     * @param  array    $bindings
     * @return bool
     */
    public static function noRead(string $sql, array $bindings = []) {
        return self::crudFunc($sql, $bindings);
    }

    // aliassen of noRead
    public static function create(string $sql, array $bindings = []) {return self::noRead($sql, $bindings);}
    public static function update(string $sql, array $bindings = []) {return self::noRead($sql, $bindings);}
    public static function delete(string $sql, array $bindings = []) {return self::noRead($sql, $bindings);}

    /**
    * This method is used to receive column information form the database table
    *
    * @param  string  $tableName        sql tableName
    * @return array with tableData
    */
    public static function showFields(string $tableName) {
        // run Query
        $sql = "show Fields FROM $tableName";
        return self::read($sql, [], TRUE);
    }

    public static function showTables(string $db = NULL) {
        if ($db == NULL) {
            $db = self::$db;
        }
        $sql = "show tables FROM $db";
        return self::read($sql, [], TRUE);
    }

    /**
     * counts the amount results that are returned from the db
     * @param   string      $tablename  an sql table name
     * @param   string      $column     an valid column in db
     * @param   string      $value      a valid string value
     * @return  int                     returns the counted columns
     */
    public static function count(string $tableName, string $column = "", string $value = "") {
        $bindings = [];
        $where = "";

        if (trim($tableName) && strpos($tableName, ' ') !== false) {
            return;

        } elseif ($column != "" && $value != "") {
            // check if key has no spaces
            if (trim($column) && strpos($column, ' ') !== false) {
                return;
            }

            $where = "WHERE $column = :value";
            $bindings["value"] = $value;
        }
        $sql = "SELECT count(*) FROM $tableName $where";
        $res = self::read($sql, $bindings , FALSE);

        return $res['count(*)'];
    }
}


?>
