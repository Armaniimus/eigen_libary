<?php

/**
 *  This class is used to contain the neccesary crud functions to interact with a database
 *
 * @property array  $lastSelect     used to hold the last select bindings
*/
class DB_Essentials {
    public $lastSelect = [];

    /**
     * used to insert data in the database
     *
     * @param string    $sql the sql query
     * @param array     $bindings (optional) the bindings used in the query
     * @return int      last insert id
     */
    public function createData(string $sql, array $bindings = []) {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($bindings);
        return $this->pdo->lastInsertId();
    }

    /**
     * reads data from a database
     *
     * @param string    $sql the sql query
     * @param array     $bindings (optional) the bindings for the query
     * @param boolean   $multiple (optional) if you want multiple rows or not
     * @return array    the data from the database
     */
    public function readData(string $sql, array $bindings = [], bool $multiple = true) {

        $sth = $this->pdo->prepare($sql);
        $sth->execute($bindings);

        $this->lastSelect = compact("bindings", "sql");

        if($multiple) {
            return $sth->fetchAll();

        } else {
            return $sth->fetch();
        }
    }

    /**
     * updates data in the database
     *
     * @param string    $sql the sql query
     * @param array     $bindings (optional) the bindings for the query
     * @return int      last insert id
     */
    public function updateData(string $sql, array $bindings = []) {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($bindings);
        return $this->pdo->lastInsertId();
    }

    /**
     * deletes data in the database
     *
     * @param string    $sql the sql query
     * @param array     $bindings (optional) the bindings for the query
     * @return bool     if the query completed or not
     */
    public function deleteData(string $sql, array $bindings = []) {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($bindings);
    }
}
?>
