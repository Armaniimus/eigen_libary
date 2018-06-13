<?php

class phpUtilities {

    function __construct() {

    }

    public function ConvertNumericData($option = 0, $array = NULL, $key = 'product_price', $string = NULL) {
        if ($option == 0) {

            // Loop and convert all shown data
            for ($i=0; $i < count($array); $i++) {
                $array[$i]["$key"] = "&euro;" . $array[$i]["$key"];
                $array[$i]["$key"] = str_Replace(".", ",", $array[$i]["$key"]);
            }
            return $array;

        } elseif ($option == 1 || $option == "update" || $option == "create") {
            // $data is a string
            $string = str_Replace(",", ".", $string);
            $string = str_Replace("&euro;", "", $string);
            $string = str_Replace("€", "", $string);

            return $string;
        }
    }

    /****
    ** description -> Selects specified data from an array
    ** relies on methods -> Null

    ** Requires -> $array, $code
    ** string variables -> $code
    ** array variables -> $array
    ****/
    public function SelectWithCodeFromArray($array, $code) {
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

    public function AssocToNumericConversion($AssocArray) {
        $resultArray = [];
        $i = 0;
        foreach ($AssocArray as $key => $value) {
            $resultArray[$i] = $value;
            $i++;
        }

        return $resultArray;
    }

    public function SelectFromAssoc($AssocArray, $code) {
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
