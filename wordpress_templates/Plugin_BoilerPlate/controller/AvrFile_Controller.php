<?php
if ( !defined("ABSPATH") ) {
    die("Access violation");
}

/**
 *
 */
class AvrFile_Controller {
    public function __construct() {
        require_once AvrFile_Root . "/model/AvrFile_Model.php";
        $this->model = new AvrFile_Model();
    }

    private function run() {

    }
}
