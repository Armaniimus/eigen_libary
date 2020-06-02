<?php
/**
 *
 */
class Banana {
    private $size;
    function __construct($size = 1) {
        $this->size = $size;
    }

    function get_info() {
        return 'banana size = ' . $this->size . '<br />';
    }
}

?>
