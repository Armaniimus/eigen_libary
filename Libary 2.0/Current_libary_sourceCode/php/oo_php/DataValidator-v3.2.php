<?php
    require_once "traits\ValidatePHP_ID.php";
    class DataValidator {
        private $dataTypesArray;
        private $nullDataArray;
        private $columnNames;

        public function __construct($columnNames = NULL, $dataTypesArray = NULL, $nullDataArray = NULL) {
            $this->dataTypesArray = $dataTypesArray;
            $this->nullDataArray = $nullDataArray;
            $this->columnNames = $columnNames;
        }

        ####################
        #included trait
        use ValidatePHP_ID;

        ####################
        #front-end methods
        public function GetHtmlValidateData($dataTypesArray = NULL) {
            if ($dataTypesArray == NULL) {
                $dataTypesArray = $this->dataTypesArray;
            }
            // return Html Validation shizzle

            for ($i=0; $i<count($dataTypesArray); $i++) {

                // int tests
                if (strpos($dataTypesArray[$i], 'int') !== false) {
                    if (strpos($dataTypesArray[$i], 'tinyint') !== false) {
                        $dataTypesArray[$i] = $this->ValidateHTMLInt($dataTypesArray[$i], 'tiny');

                    } else if (strpos($dataTypesArray[$i], 'smallint') !== false) {
                        $dataTypesArray[$i] = $this->ValidateHTMLInt($dataTypesArray[$i], 'small');

                    } else if (strpos($dataTypesArray[$i], 'mediumint') !== false) {
                        $dataTypesArray[$i] = $this->ValidateHTMLInt($dataTypesArray[$i], 'medium');

                    } else if (strpos($dataTypesArray[$i], 'bigint') !== false) {
                        $dataTypesArray[$i] = $this->ValidateHTMLInt($dataTypesArray[$i], 'big');

                    } else if (strpos($dataTypesArray[$i], 'int') !== false) {
                        $dataTypesArray[$i] = $this->ValidateHTMLInt($dataTypesArray[$i], '');
                    }
                }

                // StringTests
                 else if (strpos($dataTypesArray[$i], 'varchar') !== false) {
                    $dataTypesArray[$i] = $this-> ValidateHTMLVarchar($dataTypesArray[$i]);
                }

                // Double/decimal Number Tests
                else if (strpos($dataTypesArray[$i], 'decimal') !== false) {
                    $dataTypesArray[$i] = $this->ValidateHTMLDecimal($dataTypesArray[$i]);
                }

                // Date/time tests
                 else if (strpos($dataTypesArray[$i], 'date') !== false) {
                    $dataTypesArray[$i] = $this->ValidateHTMLDate($dataTypesArray[$i]);
                }
            }
            return $dataTypesArray;
        }

        private function ValidateHTMLVarchar($data) {
            $data = $this->PrepValidateVarchar($data);
            $data = "type='text' maxlength='$data' pattern='[^\s$][A-Za-z0-9!@#$%\^&*\s.,:;+-()]*'";

            return $data;
        }

        private function ValidateHTMLInt($data, $option = '') {

            if ($option === 'tiny') {
                $data = $this->PrepValidateTinyInt($data);

            } else if ($option === 'small') {
                $data = $this->PrepValidateSmallInt($data);

            } else if ($option === 'medium') {
                $data = $this->PrepValidateMediumInt($data);

            } else if ($option === '') {
                $data = $this->PrepValidateInt($data);

            } else if ($option === 'big') {
                $data = $this->PrepValidateBigInt($data);
            }

            // set min and max
            $min = $data["min"];
            $max = $data["max"];

            $data = "type='number' step='1' min='$min' max='$max'";
            return $data;
        }

        private function ValidateHTMLDecimal($data) {
            // get numericData
            $data = $this->prepValidateDecimal($data);

            // set decimal and max
            $decimal = $data["decimal"];
            $max = $data["max"];

            $data = "type='number' max='$max' step='$decimal'";
            return $data;

        }

        private function ValidateHTMLDate($data) {
            $data = "type='date'";
            return $data;
        }

        public function ValidateHTMLNotNull($nullDataArray = NULL, $selectCode = NULL) {

            if ($nullDataArray == NULL) {
                $nullDataArray = $this->nullDataArray;
            }

            if ($selectCode !== NULL) {
                $nullDataArray = $this->SelectWithCodeFromArray($nullDataArray, $selectCode);
            }

            for ($i=0; $i < count($nullDataArray); $i++) {
                if (strpos($nullDataArray[$i], 'YES') !== false) {
                    $nullDataArray[$i] = "";

                } else if (strpos($nullDataArray[$i], 'NO') !== false) {
                    $nullDataArray[$i] = "required";
                }
            }

            return $nullDataArray;
        }

        ####################
        #back-end methods
        public function ValidatePHPRequired($assocArray, $nullDataArray = NULL, $columnNames = NULL, $selectCode = NULL) {
            if ($nullDataArray == NULL) {
                $nullDataArray = $this->nullDataArray;
            }

            if ($columnNames == NULL) {
                $columnNames = $this->columnNames;
            }

            if ($selectCode !== NULL) {
                $columnNames = $this->SelectWithCodeFromArray($columnNames, $selectCode);
                $nullDataArray = $this->SelectWithCodeFromArray($nullDataArray, $selectCode);
            }

            for ($i=0; $i<count($columnNames); $i++) {
                // test each columnName inside assoc array one at a time

                if ($nullDataArray[$i] == "YES") {
                    continue;
                }

                else if ($nullDataArray[$i] == "NO") {
                    if (!isset($assocArray[$columnNames[$i]])) {
                        return FALSE;
                    }
                    $test = $this->TestIfNotEmpty( $assocArray[$columnNames[$i]] );

                    // if one of the tests fails return false
                    if ($test == FALSE) {
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }

        private function TestMinimalLength($length, $string = "") {
            if (strlen($string) < $length) {
                return FALSE;

            } else {
                return TRUE;
            }
        }

        private function TestMaximumLength($length, $string = "") {
            if (strlen($string) > $length) {
                return FALSE;

            } else {
                return TRUE;
            }
        }

        private function ValidatePHPFloat_Double($string) {
            return is_numeric($string);
        }

        private function ValidatePHPDecimal($string, $data) {
            if (is_numeric($string) ) {
                // get numericData
                $data = $this->prepValidateDecimal($data);

                // set decimal and max
                $decimal = $data["decimal"];
                $max = $data["max"];

                if (!($string < $max)) {
                    return FALSE;

                } else if ( !(($string*1) == round($string, 2)) ) {
                    return FALSE;

                } else {
                    return TRUE;
                }

            } else {
                return FALSE;
            }
        }

        private function ValidatePHPInt($string) {
            if (is_numeric($string) && floor($string) == $string) {
                return TRUE;

            } else {
                return FALSE;
            }
        }

        private function ValidatePHPBoolean($string) {
            if ($string == '1' || $string == 1 || $string === TRUE ||
            $string == '0' || $string == 0 || $string === FALSE) {
                return TRUE;

            } else {
                return FALSE;
            }
        }

        private function TestIfEmail() {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
              return TRUE;

            } else {
              return FALSE;
            }
        }

        private function TestIfNotEmpty($string) {
            $string = trim($string);

            if ( !isset($string) || $string == "" ) {
                return FALSE;

            } else {
                return TRUE;
            }

        }

        private function TestIfNoHtmlChars($string, $option = 0) {
            // forbid htmlChars
            if ($option == 0) {
                if (htmlspecialchars($string) == $string) {
                    return TRUE;

                } else {
                    return FALSE;
                }

            // convert HtmlChars
            } else if ($option == 1) {
                return htmlspecialchars($string);

            // allow html chars Not recomended
            } else if ($option == 2) {
                return $string;

            // if a wrong option is selected
            } else {
                throw new Exception("Wrong option selected in HtmlSpecialChars", $option);

            }
        }

        ####################
        #essential methods
        private function prepValidateDecimal($data) {
            $data = str_replace("decimal(", "", $data);
            $data = str_replace(")", "", $data);
            $splittedData = explode(",", $data);

            // set decimal
            $decimal = 0.1 ** $splittedData[1];

            // set max
            $multiplier = $splittedData[0]-$splittedData[1];
            $max = 10 ** $multiplier;
            $max = $max - $decimal;

            return ['decimal' => $decimal, 'max'=> $max];
        }

        private function PrepValidateVarchar($data) {
            $data = str_replace("varchar(", "", $data);
            $data = str_replace(")", "", $data);
            return $data;
        }

        private function PrepValidateChar($data) {
            $data = str_replace("char(", "", $data);
            $data = str_replace(")", "", $data);
            return $data;
        }

        private function PrepValidateTinyInt($data) {
            if (strpos($data, 'unsigned') !== false){
                $max = 255;
                $min = 0;
            } else {
                $max = 	127;
                $min = -128;
            }

            return ['min' => $min,'max'=> $max];
        }

        private function PrepValidateSmallInt($data) {
            if (strpos($data, 'unsigned') !== false){
                $max = 65535;
                $min = 0;
            } else {
                $max = 	32767;
                $min = -32768;
            }

            return ['min' => $min,'max'=> $max];
        }

        private function PrepValidateMediumInt($data) {
            if (strpos($data, 'unsigned') !== false){
                $max = 16777215;
                $min = 0;
            } else {
                $max = 	8388607;
                $min = -8388608;
            }

            return ['min' => $min,'max'=> $max];
        }

        private function PrepValidateInt($data) {
            if (strpos($data, 'unsigned') !== false){
                $max = 4294967295;
                $min = 0;
            } else {
                $max = 	2147483647;
                $min = -2147483648;
            }

            return ['min' => $min,'max'=> $max];
        }

        private function PrepValidateBigInt($data) {
            if (strpos($data, 'unsigned') !== false){
                $max = (2 ** 64)-1;
                $min = 0;
            } else {
                $max = 	(2 ** 63)-1;
                $min = (-2 ** 63);
            }

            return ['min' => $min,'max'=> $max];
        }
}
?>
