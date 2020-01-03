<?php
class Validator {
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
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
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

    public static function time($time, $format = 'H:i:s') {
        return self::dateTime($time, $format);
    }
}
?>
