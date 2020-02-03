<?php

/**
 * @summary This class is used to contain the neccesary crud functions to interact with database records
 * @property string $db    a variable to store the dbName
 * @property object $conn  a variable to store the connection
 *
 * @method setConn($server, $db, $user, $pass) this method us required to run before any other method as it sets up the connection in a static variable
 *
 * @method noRead($sql, $bindings) run a non read query

 * @method create() allias of noRead
 * @method read()   allias of noRead
 * @method update() allias of noRead
 * @method delete() allias of noRead
 *
 * @method showTables(optional $db) get info of the tables inside a/the db
 * @method showFields($table) get info of the columns/fields in the specified table;
 * @method dumpShowTables() dumps the tables to the screen
 * @method dumpShowFields() dumps the column/fields info to the screen
 *
 * @method count($tableName, $columnName, $value) counts the amount of times a the given value exists inside the specified column and table
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
    public static function setConn(string $server, string $db, string $user, string $pass) {
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

    /**
     * @param  string $sql      [description]
     * @param  array  $bindings [description]
     * @return [type]           [description]
     */
    private static function crudFunc(string $sql, array $bindings) {
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

    /**
    * This method is used to dump the showfields data intead of returning it
    */
    public static function dumpShowFields(string $tableName) {
        echo "<pre>";
        var_dump( showFields(string $tableName) );
        echo "</pre>";
    }

    public static function showTables(string $db = NULL) {
        if ($db == NULL) {
            $db = self::$db;
        }
        $sql = "show tables FROM $db";
        return self::read($sql, [], TRUE);
    }

    /**
    * This method is used to dump the showTables data intead of returning it
    */
    public static function dumpShowTables(string $db = NULL) {
        echo "<pre>";
        var_dump( showTables($db) );
        echo "</pre>";
    }

    /**
     * counts the amount of times a the given value exists inside the specified column and table
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
