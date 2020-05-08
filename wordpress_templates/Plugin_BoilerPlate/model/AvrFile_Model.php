<?php
if ( !defined("ABSPATH") ) {
    die("Access violation");
}

/**
 *
 */
class AvrFile_Model {
    public function __construct() {
        require_once AvrFile_Root . "/model/lib/Avr_FileHandler.php";
    }
}
