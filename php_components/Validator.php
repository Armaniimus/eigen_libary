<?php
class Validator {
    private $error;

    public function isID($value, $method = NULL) {
        $this->error = "Invalid ID";
        $this->priv_ID($value, $method);
    }

    public function isInt($value, $method = NULL) {
        $this->error = "Invalid integer";
        $this->priv_int($value, $method);
    }

    public function isFloat($value, $method = NULL) {
        $this->error = "Invalid float";
        $this->priv_float($value, $method);
    }

    public function isString($value, $method = NULL) {
        $this->error = "Invalid string";
        $this->priv_isString($value, $method);
    }

    public function notEmpty($value, $method = NULL) {
        $this->error = "Empty value";
        $this->priv_notEmpty($value, $method);
    }

    public function notNull($value, $method = NULL) {
        $this->error = "Null value";
        $this->priv_notNull($value, $method);
    }

    /**
     * A method used to check if a valid Sql ID is supplied
     * checks include if it has a value, is a number, is a integer, is not negative,
     *
     * @param   int     $value    a valid int number
     * @param   string  $method     an optional string to get more specific errors
     *
     * @return  bool                true or false
     */
    private function priv_ID($value, $method) {
        $test = $this->priv_int($value, $method);

        if ( $test ) {
            if ( ($value >= 0) == FALSE) {
                $message = "Value is a negative number";
                $test = FALSE;
            }

            // Test if the result was succesfull
            if (!$test) {
                $this->throwError($message, $value, $method);
            }
        }

        return $test;
    }

    private function priv_int($value, $method) {
        $test = $this->priv_float($value, $method);

        if ( $test ) {
            if ( (floor($value) == $value) == FALSE) {
                $message = "Value is not an integer number";
                $test = FALSE;
            }

            // Test if the result was succesfull
            if (!$test) {
                $this->throwError($message, $value, $method);
            }
        }

        return $test;
    }

    private function priv_float($value, $method) {
        $test = $this->priv_notEmpty($value, $method);

        if ( $test ) {
            if ( (is_numeric($value) == FALSE) ) {
                $message = "Value is not a number";
                $test = FALSE;
            }

            // Test if the result was succesfull
            if (!$test) {
                $this->throwError($message, $value, $method);
            }
        }

        return $test;
    }

    private function priv_isString($value, $method) {
        $test = $this->priv_notNull($value, $method);

        if ( $test ) {
            // Test if the result was succesfull
            if (!$test) {
                $this->throwError($message, $value, $method);
            }
        }

        return $test;
    }

    public function priv_notEmpty($value, $method) {
        $test = $this->priv_notNull($value, $method);

        if ( $test ) {
            if ($value == '') {
                $message = 'Value is ""';
                $test = FALSE;
            }

            // Test if the result was succesfull
            if (!$test) {
                $this->throwError($message, $value, $method);
            }
        }

        return $test;
    }

    private function priv_notNull($value, $method) {
        $test = TRUE;

        if ($value === NULL) {
            $message = "Value is null";
            $test = FALSE;
        }

        // Test if the result was succesfull
        if (!$test) {
            $this->throwError($message, $value, $method);
        }

        return $test;
    }

    private function throwError($message, $value, $method) {
        $error = $this->error;
        echo "<pre>";
        $errorMessage = "\nERROR->[$error] \nMESSAGE->[$message] \nVALUE->[$value] \nMETHOD->[$method]\n\n";
        trigger_error ( $errorMessage, E_USER_WARNING );
        echo "</pre>";
    }
}


$e = new Validator();
$e->isID("", "validate method");
?>
