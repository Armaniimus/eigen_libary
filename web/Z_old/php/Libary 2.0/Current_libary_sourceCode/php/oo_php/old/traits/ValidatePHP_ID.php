<?php
trait ValidatePHP_ID {

    /****
    ** description -> Selects specified data from an array
    ** relies on methods -> Null

    ** Requires -> $array, $code
    ** string variables -> $code
    ** array variables -> $array
    ****/

    public function ValidatePHP_ID($idValue, $Method = NULL) {

        // run tests and set return message if needed
        if ($idValue == "" || $idValue == NULL) {
            $message = "ID does not have a value";
            $return = FALSE;

        } else if ( (is_numeric($idValue) == FALSE) ) {
            $message = "ID is not a number";
            $return = FALSE;

        } else if ( (floor($idValue) == $idValue) == FALSE) {
            $message = "ID is not an INT";
            $return = FALSE;

        } else if ( ($idValue >= 0) == FALSE) {
            $message = "ID is a negative number";
            $return = FALSE;

        } else {
            $return = TRUE;
        }

        // Test if the result was succesfull
        if ($return == FALSE) {
            echo "<pre>";
            throw new Exception("\nERROR->[Invalid ID] \nMESSAGE->[$message] \nIDVALUE->[$idValue] \nMETHOD->[$Method]\n\n");
            echo "</pre>";
            return FALSE;

        } else {
            return TRUE;
        }

    }
}


 ?>
