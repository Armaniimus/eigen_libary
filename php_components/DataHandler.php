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

    /**
     * constructor for the datahandler
     *
     * @param string $server        Database server adress
     * @param string $database      database name for the connection
     * @param string $username      database username for the connection
     * @param string $password      database password for the connection
     */
    function __construct($server, $db, $user, $pass) {
        try {
            $this->conn = new PDO("mysql:host=$server;dbname=$db", $user, $pass);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";

        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    private function crudFunc($sql, $bindings) {
        $query = $this->conn->prepare($sql);
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
    public function read($sql, $bindings = [], $multible = TRUE) {
        $query = $this->crudFunc($sql, $bindings);

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
    public function noRead($sql, $bindings = []) {
        return $this->crudFunc($sql, $bindings);
    }

    // alias of noRead
    public function create($sql, $bindings = []) {
        return $this->noRead($sql, $bindings = [])
    }
    
    // alias of noRead
    public function update($sql, $bindings = []) {
        return $this->noRead($sql, $bindings = [])
    }

    // alias of noRead
    public function delete($sql, $bindings = []) {
        return $this->noRead($sql, $bindings = [])
    }
}


?>
