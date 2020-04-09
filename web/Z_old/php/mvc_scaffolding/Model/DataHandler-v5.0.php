<?php
    require_once "DataHandler/DB_Essentials.php";
    require_once "DataHandler/DB_Support.php";
    require_once "DataHandler/DB_Functions.php";
    /**
     *  is used as an easier to remember nameSpace
     *  contains:
     *      DB_Essentials <-- Great grantparent;
     *      DB_Support <-- Grantparent;
     *      DB_Functions <-- Parent;
    */
    class DataHandler extends DB_Functions {
        public $pdo; /** @property object $pdo  a variable to store the connection*/

        /**
         * constructor for the datahandler
         *
         * @param string $host          database host for the connection
         * @param string $database      database name for the connection
         * @param string $username      database username for the connection
         * @param string $password      database password for the connection
         * @param string $dbtype        (optional) database type for the connection
         */
        public function __construct(string $host, string $database, string $username, string $password, string $dbtype = "mysql") {
            try {
                $this->pdo = new PDO("$dbtype:host=$host;dbname=$database;charset=utf8mb4", $username, $password, [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch(PDOexeption $e) {
                $this->showError("Error: " . $e->getMessage());
            }
        }
    }

?>
