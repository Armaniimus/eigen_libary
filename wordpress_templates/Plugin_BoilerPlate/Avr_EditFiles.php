<?php
/**
 * Plugin Name: Avr_EditFiles
 * Description: A plugin to aid in development
 * Version:     1.0
 * Author:      De Hoopkaap -> Armand van Alphen
 * Author URI:  https://dehoopkaap.nl
 * License:     Private use only
 */

if ( !defined("ABSPATH") ) {
    die("Access violation");
}

// Set Configurations
define("AvrFile_Root", __DIR__);


// Load NeccesaryFiles.
require_once AvrFile_Root . "/controller/AvrFile_Controller.php";
require_once AvrFile_Root . "/model/AvrFile_Model.php";
require_once AvrFile_Root . "/model/lib/Avr_FileHandler.php";

// register hooks
require_once "hooks/AvrFile_Menu.php";
new AvrFile_Menu();

require_once "hooks/AvrFile_Enqueue.php";
new AvrFile_Enqueue();
