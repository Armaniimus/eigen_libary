<?php
    /**
     *
     */
    class Main_Controller {
        function __construct() {

        }

        public function return($params = ['']) {
            echo $params[0];
            // return $params[0];
        }

        public function home() {
            include 'View/home.php';
        }
    }

?>
