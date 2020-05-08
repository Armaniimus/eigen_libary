<?php
/**
 * Name: FileHandler
 * Copyright by: Armand van Alphen
 * Company: De hoopKaap
 * Version: 1.0
 */
class Avr_FileHandler {
    public function __construct() {

    }

    /**
     * this method checks the fileType
     * but is not garanteed to succeed and can't be fully relied opon
     * @param  string $url      a valid url
     * @return bool/string      false, fileType
     */
    public static function checkFileType(string $url) {
        if ( file_exists($url) ) {
            return filetype($url);
        }
        return FALSE;
    }

    /**
     * this function moves a file to a new directory
     * @param  string  $source    a valid url
     * @param  string  $dest      a valid url
     * @param  boolean $overwrite true, false
     * @return boolean            true, false
     */
    public static function move( string $source, string $dest, bool $overwrite = false ) {
        if ( file_exists($source) && ( !file_exists($dest) || $overwrite == true ) ) {
            return rename ($source, $dest);
        }
        return FALSE;
    }

    /**
     * this method copies a file from source to destination
     * if source exists and destination doesn't or
     * if source exists and overwrite is true
     * @param  string  $source    a valid url
     * @param  string  $dest      a valid url
     * @param  boolean $overwrite true, false
     * @return boolean            true, false
     */
    public static function copy(string $source, string $dest, bool $overwrite = false) {
        if ( file_exists($source) && ( !file_exists($dest) || $overwrite == true ) ) {
            return copy ( $source, $dest );
        }
        return FALSE;
    }

    /**
     * this function creates a new directory
     * @param  string $url a valid url
     * @return boolean     true, false
     */
    public static function create_dir(string $url) {
        if ( !file_exists($url) ){
            return mkdir($url);
        }
        return FALSE;
    }

    /**
     * reads all fileNames in the directory and gives them back in an array
     * @param  string $url a valid url
     * @return bool/array  false, array with Filenames
     */
    public static function read_dir(string $url) {
        if ( file_exists($url) ) {
            return scandir($url);
        }
        return FALSE;
    }

    /**
     * renames a folderName if source url exists and dest url doesn't
     * @param  string $source a valid url
     * @param  string $dest   a valid url
     * @return boolean        true, false
     */
    public static function update_dir(string $source, string $dest) {
        return self::move($source, $dest);
    }

    /**
     * this method deletes a folder if it exists and is empty
     * @param  string   $url    a valid url
     * @return boolean          true, false
     */
    public static function delete_dir(string $url) {
        if ( file_exists($url) ) {
            return rmdir($url);
        }
        return FALSE;
    }

    /**
     * this method is used to create a file with the given content on the given place.
     * @param  string $url     a valid url
     * @param  string $content the text to be saved
     * @return boolean         true, false
     */
    public static function create(string $url, string $content) {
        if ( !file_exists($url) ) {
            return file_put_contents($url, $content);
        } else {
            return false;
        }
    }

    /**
     * this method is used to read the contents of a file
     * @param  string $url     a valid url
     * @return string/bool     the saved text, false
     */
    public static function read(string $url) {
        if ( file_exists($url) ) {
            return file_get_contents($url);
        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to update a file
     * @param  string $url     a valid url
     * @return bool            true, false
     */
    public static function update(string $url, string $content) {
        if ( file_exists($url) ) {
            return file_put_contents($url, $content);
        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to delete a file
     * @param  string $url     a valid url
     * @return bool            true, false
     */
    public static function delete(string $url) {
        if (file_exists($url)) {
            return unlink($url);
        } else {
            return FALSE;
        }
    }
}
?>
