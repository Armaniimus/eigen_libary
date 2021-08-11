<?php
class Sanitizer {
    /**
     * filters string chars from integer
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function int($value) {
        return filter_var( trim($value), FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * filters string chars from float
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function float($value) {
        return filter_var( trim($value), FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /**
     * filters html tags from string
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function string($value) {
        return filter_var( trim($value), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);
    }

    /**
     * filters invalid chars from mail;
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function mail($value) {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

    /**
     * filters invalid chars from mail;
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function url($value) {
        return filter_var($value, FILTER_SANITIZE_URL);
    }

    /**
     * filters invalid chars from input;
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function specialChars($value) {
        return filter_var( trim($value), FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_ENCODE_HIGH);
    }

    /**
     * encodes html special chars
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function encode_specialChars($value) {
        return htmlspecialchars( trim($value) );
    }

    /**
     * decodes html special chars
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function decode_specialChars($value) {
        return htmlspecialchars_decode( trim($value) );
    }

    /**
     * sanitizes a date
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static  function date($value) {
        $value = self::text($value);

        try {
            $array = explode('-', $value);
            $year = $array[0];
            $month = $array[1];
            $day = $array[2];

            if(!checkdate($month, $day, $year)) {
                return $value;
            }
        }

        //runs if an error occurred or no valid date is found;
        return '0000-00-00';
    }

    /**
     * sanitizes a time
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function time($value) {
        $value = self::text($value);

        try {
            $array = explode(':', $value);
            if (count($array) == 2) {
                $hour = (int) $array[0];
                $min  = (int) $array[1];

                if ($hour >= 0 && $hour <= 23) {
                    if ($min >= 0 && $min <= 59) {
                        return $value;
                    }
                }
            }
        }

        //runs if an error occurred or no valid date is found;
        return '0:00';
    }

    /**
     * sanitizes a hexadecimal
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public static function hexadecimal($value)  {
        $value = self::text($value);
        if ( preg_match('/^#?([a-f0-9]{3}|[a-f0-9]{6})$/') ) {
            return $value;
        }

        return '#000000';
    }
}
?>
