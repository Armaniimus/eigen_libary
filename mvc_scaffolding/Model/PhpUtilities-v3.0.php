<?php

class PhpUtilities {
    function __construct() {

    }

    /**
     * this method is used to specify how many decimals you want in a float equal to toFixed in JS
     * @param  float  $number   the number to cut to a specific decimal amount
     * @param  int    $decimals a number to use how many decimals you want
     * @return float  return the decimal number cut to a specified amount
     */
    public function toFixed($number, $decimals) {
        return number_format($number, $decimals, ".", "");
    }

    /***
    * $array expects an 2dimensional numeric array with assoc arrays in it
    * $key expects an string (is used as key for the inner assoc arrays)
    *
    * @Description
    * converts regular . to , and adds euro sighn in front */

    /**
     * this method is used to convert numbers in a 2d assocarray from regular US standard to NL standard
     *
     * @param  array  $array a 2d assocarray to be able to loop through the array looks like myArray[0]['valuta']
     * @param  string $key   the key to be used to select how to loop through the array
     *
     * @return string        returns the converted array
     */
    public function convert_NormalToEuro_2DArray($array = NULL, $key = NULL) {
        // Convert to . to , with euro
        for ($i=0; $i < count($array); $i++) { // Loop and convert all shown data
            $array[$i]["$key"] = "&euro;" . $array[$i]["$key"];
            $array[$i]["$key"] = str_Replace(".", ",", $array[$i]["$key"]);
        }
        return $array;
    }

    /**
     * this method is used to convert numbers in a 2d assocarray from NL standard to regular US standard
     * @param  array  $array a 2d assocarray to be able to loop through the array looks like myArray[0]['valuta']
     * @param  string $key   the key to be used to select how to loop through the array
     *
     * @return string        returns the converted array
     */
    public function convert_EuroToNormal_2DArray($array = NULL, $key = NULL) {
        // Convert to . to , with euro
        for ($i=0; $i < count($array); $i++) { // Loop and convert all shown data
            $array[$i]["$key"] = str_Replace(",", ".", $array[$i]["$key"]);
            $array[$i]["$key"] = str_Replace("&euro;", "", $array[$i]["$key"]);
            $array[$i]["$key"] = str_Replace("€", "", $array[$i]["$key"]);
        }
        return $array;
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

    /****
    ** description -> Selects specified data from an array
    ** relies on methods -> Null

    ** Requires -> $array, $code
    ** string variables -> $code
    ** array variables -> $array
    ****/
    public function selectWithCodeFromArray($array, $code) {
        $splittedCode = str_split($code);
        $return = []; // <--- is used to store the output data
        $y=0; // <--- is used to count in which position the next datapiece needs to go

        for ($i=0; $i<count($array); $i++) {
            if ($splittedCode[$i] == 0) {

            }
            else if ($splittedCode[$i] == 1) {
                $return[$y] = $array[$i];
                $y++;
            }
            else if ($splittedCode[$i] == 2) {
                //runs till the end of the array and writes everything inside the array
                for ($i=$i; $i<count($array); $i++) {
                    $return[$y] = $array[$i];
                    $y++;
                }
            }
            else if ($splittedCode[$i] == 3) {
                //runs till the end of the array and writes nothings
                for ($i=$i; $i<count($array); $i++) {

                }
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
     * This method can be used to select something from a assocArray
     * @param  array  $AssocArray this is an assoc array
     * @param  string $code       this code can be used to select what you want from the array
     *                            each character represents 1 array position.
     *                            a 0 means ignore this position
     *                            a 1 means put this position in the return array
     * @return array              an array filtered by use of the supplied code.
     */
    public function selectFromAssoc($AssocArray, $code) {
        $i = 0;
        $y = 0;
        foreach ($AssocArray as $key => $value) {
            if ($code[$i] === "0") {

            }

            else if ($code[$i] === "1") {
                $resultArray[$key] = $value;
                $y++;
            }
            $i++;
        }

        return $resultArray;
    }
}
