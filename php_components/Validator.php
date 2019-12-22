<?php
class Validator {
    private $dev;
    private $error;
    public $errorMessage;

    public function __construct($dev = FALSE) {
        $this->dev = $dev;
    }

    public function isID($value, $method = NULL) {
        $this->setupTest("invalid ID");
        $this->priv_ID($value, $method);
    }

    public function isInt($value, $method = NULL) {
        $this->setupTest("invalid Int");
        $this->priv_int($value, $method);
    }

    public function isFloat($value, $method = NULL) {
        $this->setupTest("invalid Float");
        $this->priv_float($value, $method);
    }

    public function isString($value, $method = NULL) {
        $this->setupTest("invalid String");
        $this->priv_string($value, $method);
    }

    public function notEmpty($value, $method = NULL) {
        $this->setupTest("Empty Value");
        $this->priv_notEmpty($value, $method);
    }

    public function isTime($value, $method = NULL) {
        $this->setupTest("invalid Time");
        $this->priv_time($value, $method);
    }

    public function isDate($value, $method = NULL) {
        $this->setupTest("invalid Date");
        $this->priv_date($value, $method);
    }

    public function notNull($value, $method = NULL) {
        $this->setupTest("Null value");
        $this->priv_notNull($value, $method);
    }

    private function setupTest(string $error) {
        $this->error = $error;
        $this->errorMessage = "";
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

    private function priv_string($value, $method) {
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

    private function priv_date($value, $method) {
        $test = TRUE;
        $date2 = str_replace("/", "-", $value);
        $date3 = str_replace("\\", "-", $date2);
        $dateArray = explode("-", $date3);

        //check if there are the right amount of items
        if ( count($dateArray) !== 3 ) {
            $message = "date doesn't have 3 items when exploded on /,\\ and -";
            $test = FALSE;

        } else {
            for ($i=0; $i<3; $i++) {
                if ($i == 0) {
                    if (strlen($dateArray[$i]) != 4) {
                        $message = "year is not of correct length (4 chars)";
                        $test = FALSE;
                    }

                } else {
                    if (strlen($dateArray[$i]) != 2) {
                        $message = "month or day is not 2 chars long";
                        $test = FALSE;
                    }
                }

                //check if array items are integers
                if ( !$this->priv_int($dateArray[$i], $method) ) {
                    $message = "position $i of the date not a valid integer 0=year, 1=month, 2=day";
                    $test = FALSE;
                } else {
                    $testDot = explode(".", $dateArray[$i]);
                    if (count($testDot) > 1) {
                        $message = "position $i of the date contains an illegal dot 0=year, 1=month, 2=day";
                        $test = FALSE;
                    }
                }
            }

            //check valid day
            if ($dateArray[2] > 31 || $dateArray[2] < 1) {
                $message = "not a valid day";
                $test = FALSE;

            //check valid month
            } else if ($dateArray[1] > 12 || $dateArray[1] < 1) {
                $message = "not a valid month";
                $test = FALSE;
            }
        }

        if (!$test) {
            $this->throwError($message, $value, $method);
        }

        return $test;
    }

    private function priv_time($value, $method) {
        $test = TRUE;
        $timeArray = explode(":", $value);

        // test for correct length
        if ( count($timeArray) != 2 ) {
            $test = FALSE;
            $message = "timeArray doesn't have 2 items when exploded on :";

        // test if hour has correct length
        } else if ( strlen($timeArray[0]) != 2) {
            $test = FALSE;
            $message = "Hour has an incorrect char length";

        // minute has an incorrect length
        } else if ( strlen($timeArray[1]) != 2) {
            $test = FALSE;
            $message = "Minute has an incorrect char length";

        // check if integer
        } else if ( !$this->priv_int($timeArray[0], $method) || !$this->priv_int($timeArray[1], $method) ) {
            $test = FALSE;
            $message = "minute or hour is not an integer";

        // check if hour is good
        } else if ($timeArray[0] > 23 || $timeArray[0] < 0) {
            $test = FALSE;
            $message = "hour has a to low or high value";

        // check if minute is good
        } else if ($timeArray[1] > 59 || $timeArray[1] < 0) {
            $test = FALSE;
            $message = "minute has a to low or high value";
        }

        if (!$test) {
            $this->throwError($message, $value, $method);
        }

        return $test;
    }

    private function throwError($message, $value, $method) {
        $error = $this->error;
        $this->errorMessage = "\nERROR->[$error] \nMESSAGE->[$message] \nVALUE->[$value] \nMETHOD->[$method]\n\n";

        if ($this->dev == true) {
            echo "<pre>";
            trigger_error ( $this->errorMessage, E_USER_WARNING );
            echo "</pre>";
        }
    }
}
?>
