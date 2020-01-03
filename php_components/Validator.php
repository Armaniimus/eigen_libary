<?php
class Validator {
    private static $dev = FALSE;
    public static $message = '';

    public static function dev_on() {
        self::$dev = TRUE;
    }

    public static function id($value) {
        return self::int($value, 0);
    }

    public static function int($value, $min = NULL, $max = NULL) {
        $value = trim($value);
        $min =($min === NULL ? $value : trim($min) );
        $max = ($max === NULL ? $value : trim($max) );

        $options = [
            'options' => [
                'min_range' => $min,
                'max_range' => $max,
            ]
        ];
        return filter_var($value, FILTER_VALIDATE_INT, $options);
    }

    public static function float($value, $min = NULL, $max = NULL) {
        $value = trim($value);
        $min =($min === NULL ? $value : trim($min) );
        $max = ($max === NULL ? $value : trim($max) );

        $result = FALSE;
        if ($float = filter_var($value, FILTER_VALIDATE_FLOAT) ) {
            if ($float >= $min && $float <= $max) {
                $result = $float;
            }
        };
        return $result;
    }

    public static function bool($value) {
        $value = trim($value);
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        return $bool;
    }

    public static function mail($value) {
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function dateTime($dateTime, $format = 'Y-m-d H:i:s') {
        $date = trim($dateTime);
        $format = trim($format);

        $dt = DateTime::createFromFormat($format, $dateTime);
        return $dt && $dt->format($format) == $dateTime;
    }

    public static function date($date, $format = 'Y-m-d') {
        return self::dateTime($date, $format);
    }

    public static function time($time, $format = 'H:i') {
        return self::dateTime($time, $format);
    }

    public static function valArray(array $data, array $settings, $dev = FALSE) {
        $result = TRUE;
        if (self::$dev == TRUE) {
            $dev = TRUE;
        }

        $message = '';
        foreach ($settings as $key => $values) {
            $valArray = self::valArray_init($values);

            /*
             if Data is filled
               check type
             elseif data is required
               return false*/
            if (isset($data[$key]) && $data[$key] != "") {
                $typeRes = self::valArray_Type($valArray["type"], $valArray["min"], $valArray["max"], $data[$key]);
                if ($typeRes == FALSE) {
                    $message .= "$key == invalid ".$valArray["type"]."<br>";
                    $result = FALSE;
                }
            } elseif ($valArray["required"] == TRUE) {
                $result = FALSE;
                $message .= "$key == required<br>";
            }
        }

        if ($message != "") {
            $message = "[ <b>Validation Errors</b> ]<br>" . $message;
            self::$message = $message;
            if ($dev) {
                echo $message;
            }
        }

        return $result;
    }

    /**
     * method tests if postsExist
     * @param  array  $fields   an array with strings to be used as key on the $_POST
     * @return bool             true, false
     */
    public static function requiredArray(array $fields, $dev = FALSE) {
        if (self::$dev == TRUE) {
            $dev = True;
        }

        $test = true;
        foreach ($fields as $key => $value) {
            !empty($_POST[ $key ]) ? : $test = false;

            if ($dev == TRUE && $test == false) {
                echo "\$_POST['$key'] is not set";
                break;
            }
        }
        return $test;
    }

    /**
     * retrieve posts based on a array of strings
     * @param array $fields     an array of strings to be used as key
     * @return array            an aray with the data of the post
     */
    public static function Get_Post(array $fields) {
        $data = [];
        foreach ($fields as $key => $value) {
            $data[ $key ] = ( empty($_POST[ $key ]) ) ? "" : $_POST[ $key ];
        }
        return $data;
    }

    private static function valArray_init($values) {
        //Set values
        $valuesArray = explode(" ", strtolower($values) );
        $type = $valuesArray[0];
        $required = FALSE;
        $min = "";
        $max = "";
        for ($i=1; $i<count($valuesArray); $i++) {
            if ($valuesArray[$i] == "required") {
                $required = TRUE;

            } elseif (strpos("min:") !== FALSE ) {
                $minArray = explode(":", $valuesArray[$i] );
                $min = $minArray[1];

            } elseif (strpos("max:") !== FALSE ) {
                $maxArray = explode(":", $valuesArray[$i] );
                $max = $maxArray[1];
            }
        }
        return ["type" => $type, "required" => $required, "min" => $min, "max" => $max];
    }

    private static function valArray_Type($type, $min, $max, $dataItem) {
        switch ($type) {
            case "id":
                $result = self::id($dataItem);
                break;
            case "int":
            case "integer":
                $result = self::int($dataItem, $min, $max);
                break;
            case "float":
            case "decimal":
                $result = self::float($dataItem, $min, $max);
                break;
            case "bool":
            case "boolean":
                $result = self::bool($dataItem);
                break;
            case "mail":
                $result = self::mail($dataItem);
                break;
            case "dateTime":
                $result = self::dateTime($dataItem);
                break;
            case "date":
                $result = self::date($dataItem);
                break;
            case "time":
                $result = self::time($dataItem);
                break;
            case "string":
                $result = $dataItem;
                break;
            default:
                break;
        }

        return $result;
    }
}
?>
