<?php

class PhpUtilities {
    function __construct() {

    }

    /**
     * this method is used to specify how many decimals you want in a float equal to toFixed in JS
     * @param  float  $number   the number to cut to a specific decimal amount
     * @param  int    $decimals a number to use how many decimals you want
     * @return float            return the decimal number cut to a specified amount
     */
    public function toFixed($number, $decimals) {
        return number_format($number, $decimals, ".", "");
    }

    /**
     * convert a US standard decimal to NL standard
     * @param  string $string  a value to to be converted
     * @return string          the converted string
     */
    public function convert_NormalToEuro($string) {
        // Convert to . to , with euro
            $string = "&euro;" . $string;
            $string = str_Replace(".", ",", $string);

        return $string;
    }

    /**
     * convert a NL standard to regular US standard
     * @param  string $string  a value to to be converted
     * @return string          the converted string
     */
    public function convert_EuroToNormal($string) {
        // $data is a string
        $string = str_Replace(",", ".", $string);
        $string = str_Replace("&euro;", "", $string);
        $string = str_Replace("€", "", $string);

        return $string;
    }

    /**
    *  description -> Selects specified data from an array
    *
    *  @param string $code a code to define what to get from array
    *       0 ignore position
    *       1 use position
    *       2 use this and all positions after
    *       3 ignore this and all positions after
    *  @param array  $array a numeric array
    *  @return array        a numeric array
    */
    public function selectFromArray($array, $code) {
        $splittedCode = str_split($code);
        $return = []; // <--- is used to store the output data
        $y=0; // <--- is used to count in which position the next datapiece needs to go

        for ($i=0; $i<count($array); $i++) {
            if ($splittedCode[$i] == 0) {
                continue;

            } else if ($splittedCode[$i] == 1) {
                $return[$y] = $array[$i];
                $y++;

            } else if ($splittedCode[$i] == 2) {
                //runs till the end of the array and writes everything inside the array
                for ($i=$i; $i<count($array); $i++) {
                    $return[$y] = $array[$i];
                    $y++;
                }

            } else if ($splittedCode[$i] == 3) {
                break;
                // //runs till the end of the array and writes nothings
                // for ($i=$i; $i<count($array); $i++) {
                //
                // }
            } else {
                throw new Exception("incorrect code given to selectFromArray()", 1);
            }
        }
        return $return;
    }

    /**
     * this method can convert a associative array to a numberic array
     * @param  array $AssocArray  this need to be an associative array
     * @return array              this is a numeric array
     */
    public function assocToNumericConversion($AssocArray) {
        $resultArray = [];
        $i = 0;
        foreach ($AssocArray as $key => $value) {
            $resultArray[$i] = $value;
            $i++;
        }

        return $resultArray;
    }

    /**
     * retrieve posts based on a array of strings
     * @param array $fields     an array of strings to be used as key
     * @return array            an aray with the data of the post
     */
    private function Get_Post(array $fields) {
        $data = [];
        for ($i=0; $i<count($fields); $i++) {
            $data[ $fields[$i] ] = ( empty($_POST[ $fields[$i] ]) ) ? "" : $_POST[ $fields[$i] ];
        }
        return $data;
    }

    /**
     * method tests if postsExist
     * @param  array  $fields   an array with strings to be used as key on the $_POST
     * @return bool             true, false
     */
    private function checkPostsExist(array $fields) {
        $test = true;
        for ($i=0; $i<count($fields); $i++) {
            !empty($_POST[ $fields[$i] ]) ? : $test = false;
        }
        return $test;
    }
}
