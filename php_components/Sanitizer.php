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
}
?>
