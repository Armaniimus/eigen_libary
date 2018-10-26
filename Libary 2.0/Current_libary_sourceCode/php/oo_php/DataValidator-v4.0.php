<?php
require_once "traits/ValidatePHP_ID-v2.0.php";
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
    use validatePHP_ID;

    ####################
    #front-end methods

    /**
     * This method is used to generate valid frontend validation based on the columnTypes in a mysql database
     * @param  array $dataTypesArray     an array of valid sql datatypes is needed
     * @return array                     an array of valid frontend validation
     */
    public function getHtmlValidateData($dataTypesArray = NULL) {
        if ($dataTypesArray == NULL) {
            $dataTypesArray = $this->dataTypesArray;
        }
        // return Html Validation shizzle

        for ($i=0; $i<count($dataTypesArray); $i++) {

            // int tests
            if (strpos($dataTypesArray[$i], 'int') !== false) {
                if (strpos($dataTypesArray[$i], 'tinyint') !== false) {
                    $dataTypesArray[$i] = $this->validateHTMLInt($dataTypesArray[$i], 'tiny');

                } else if (strpos($dataTypesArray[$i], 'smallint') !== false) {
                    $dataTypesArray[$i] = $this->validateHTMLInt($dataTypesArray[$i], 'small');

                } else if (strpos($dataTypesArray[$i], 'mediumint') !== false) {
                    $dataTypesArray[$i] = $this->validateHTMLInt($dataTypesArray[$i], 'medium');

                } else if (strpos($dataTypesArray[$i], 'bigint') !== false) {
                    $dataTypesArray[$i] = $this->validateHTMLInt($dataTypesArray[$i], 'big');

                } else if (strpos($dataTypesArray[$i], 'int') !== false) {
                    $dataTypesArray[$i] = $this->validateHTMLInt($dataTypesArray[$i], '');
                }
            }

            // StringTests
             else if (strpos($dataTypesArray[$i], 'varchar') !== false) {
                $dataTypesArray[$i] = $this-> validateHTMLVarchar($dataTypesArray[$i]);
            }

            // Double/decimal Number Tests
            else if (strpos($dataTypesArray[$i], 'decimal') !== false) {
                $dataTypesArray[$i] = $this->validateHTMLDecimal($dataTypesArray[$i]);
            }

            // Date/time tests
             else if (strpos($dataTypesArray[$i], 'date') !== false) {
                $dataTypesArray[$i] = $this->validateHTMLDate();
            }
        }
        return $dataTypesArray;
    }

    /**
     * a method to get a frontend validation for a varchar field in mysql
     * @param  string  $data   needs something like "varchar([int])"
     * @return string  returns a valid piece of html to use in a input element
     */
    private function validateHTMLVarchar($data) {
        $data = $this->prepValidateVarchar($data);
        $data = "type='text' maxlength='$data' pattern='[^\s$][A-Za-z0-9!@#$%\^&*\s.,:;+-()]*'";

        return $data;
    }

    /**
     * Method to select which integervalidation method needs to be used,
     * then call it and generate the frontend validation with its results
     * @param  string $data   a string of the sqlDataType
     * @param  string $option a string with a valid integersize
     * @return string         a string of valid html to use in a input element
     */
    private function validateHTMLInt($data, $option = '') {

        if ($option === 'tiny') {
            $data = $this->prepValidateTinyInt($data);

        } else if ($option === 'small') {
            $data = $this->prepValidateSmallInt($data);

        } else if ($option === 'medium') {
            $data = $this->prepValidateMediumInt($data);

        } else if ($option === '') {
            $data = $this->prepValidateInt($data);

        } else if ($option === 'big') {
            $data = $this->prepValidateBigInt($data);
        }

        // set min and max
        $min = $data["min"];
        $max = $data["max"];

        $data = "type='number' step='1' min='$min' max='$max'";
        return $data;
    }

    /**
     * this method is used to create frontend validation based on a mysqlType
     * @param  string $data needs a string with a valid decimal mysql type
     * @return string       method return html that can be used in a inputElement
     */
    private function validateHTMLDecimal($data) {
        // get numericData
        $data = $this->prepValidateDecimal($data);

        // set decimal and max
        $decimal = $data["decimal"];
        $max = $data["max"];

        $data = "type='number' max='$max' step='$decimal'";
        return $data;

    }

    /**
     * this method is used to create frontend validation based on a mysqlType
     * @return string       method returns html that can be used in a inputElement
     */
    private function validateHTMLDate() {
        $data = "type='date'";
        return $data;
    }

    /**
     * this method is used to generated frontend validation
     * and specigicly to say if a field is required or is optional
     * also some fields from the array can be leftout if required
     * @param array  $nullDataArray     expects an array with nullvalues in them
     * @param string $selectionCode     expects an string with the numbers 0123
     *                                  0 for don't get the data on this position
     *                                  1 for get the data on this position
     *                                  2 for get data on this position and all after it
     *                                  3 for dont get this data or any after it
     *
     * @return array                    returns an array with html strings which can be used in inputElements
     */
    public function validateHTMLNotNull($nullDataArray = NULL, $selectCode = NULL) {

        if ($nullDataArray == NULL) {
            $nullDataArray = $this->nullDataArray;
        }

        if ($selectCode !== NULL) {
            $nullDataArray = $this->selectWithCodeFromArray($nullDataArray, $selectCode);
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

    /**
     * this method is used to check if all fields that are required are correctly filled in the backend
     * this is checked based on a nullvalues array that can be supplied by the dataHandler
     * @param array  $assocArray        array with data from a $_POST or $_GET for example
     * @param array  $nullDataArray     array with nullvalues to be used
     * @param array  $columnNames       array with columnNames
     * @param string $selectionCode     expects an string with the numbers 0123
     *                                  0 for don't get the data on this position
     *                                  1 for get the data on this position
     *                                  2 for get data on this position and all after it
     *                                  3 for dont get this data or any after it
     *
     * @return bool                     true, false
     */
    public function validatePHPRequired($assocArray, $nullDataArray = NULL, $columnNames = NULL, $selectCode = NULL) {
        if ($nullDataArray == NULL) {
            $nullDataArray = $this->nullDataArray;
        }

        if ($columnNames == NULL) {
            $columnNames = $this->columnNames;
        }

        if ($selectCode !== NULL) {
            $columnNames = $this->selectWithCodeFromArray($columnNames, $selectCode);
            $nullDataArray = $this->selectWithCodeFromArray($nullDataArray, $selectCode);
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
                $test = $this->testIfNotEmpty( $assocArray[$columnNames[$i]] );

                // if one of the tests fails return false
                if ($test == FALSE) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    /**
     * this method is used to check if the minimal amount of character in the string has been reached
     * @param int       $length a integer
     * @param string    $string a valid string
     *
     * @return bool     true, false
     */
    private function testMinimalLength($length, $string = "") {
        if (strlen($string) < $length) {
            return FALSE;

        } else {
            return TRUE;
        }
    }

    /**
     * this method is used to see if the string has less characters than the maximum given length
     * @param int       $length a integer
     * @param string    $string a valid string
     *
     * @return bool     true, false
     */
    private function testMaximumLength($length, $string = "") {
        if (strlen($string) > $length) {
            return FALSE;

        } else {
            return TRUE;
        }
    }

    /**
     * this method returns true or false based
     * on if given value is a string and has only numbers in it or is a double or integer
     * @param  any      $val    a double, integer, array, or string can be supplied
     *
     * @return bool             true,false
     */
    private function validatePHPFloat_Double($val) {
        return is_numeric($val);
    }

    /**
     * this method is used to validate that the supplied val is equal to the supplied mysql decimal type.
     * @param  any    $val    the value to be tested
     * @param  string $data   an sql decimalType like Decimal(5,2) for a number like 999.99
     *
     * @return bool          true,false
     */
    private function validatePHPDecimal($val, $data) {
        if (is_numeric($val) ) {
            // get numericData
            $data = $this->prepValidateDecimal($data);

            // set decimal and max
            $decimal = $data["decimal"];
            $max = $data["max"];

            if (!($string < $max)) {
                return FALSE;

            } else if ( !(($val*1) == round($val, 2)) ) {
                return FALSE;

            } else {
                return TRUE;
            }

        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to validate if a value can be succesfully validated as a integer
     * @param  any   $val   this is the value to be tested
     *
     * @return bool         true, false
     */
    private function validatePHPInt($val) {
        if (is_numeric($val) && floor($val) == $val) {
            return TRUE;

        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to validate if a value can be succesfully validated as a boolean
     * @param  any   $val   this is the value to be tested
     *
     * @return bool         true, false
     */
    private function validatePHPBoolean($val) {
        if ($val == '1' || $val == 1 || $val === TRUE ||
        $val == '0' || $val == 0 || $val === FALSE) {
            return TRUE;

        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to validate if a value can be succesfully validated as a email
     * @param  any   $email this is the value to be tested
     *
     * @return bool         true, false
     */
    private function testIfEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
          return TRUE;

        } else {
          return FALSE;
        }
    }

    /**
     * this method is used to validate if the suplied value is not empty or only spaces
     * @param  any   $val this is the value to be tested
     *
     * @return bool         true, false
     */
    private function testIfNotEmpty($val) {
        $val = trim($val);

        if ( !isset($val) || $val == "" ) {
            return FALSE;

        } else {
            return TRUE;
        }
    }

    /**
     * this method is used to validate if the suplied value does not contain html
     * but can also be used to extract html from the string
     *
     * @param  string  $string  this is the value to be tested
     * @param  int     $option  this is the option to be selected
     *                 0 = test if htmlChars are present
     *                 1 = extract html from the string
     *
     * @return bool with option 0
     * @return string with option 1 and 2
     */
    private function testIfNoHtmlChars($string, $option = 0) {
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

    /**
     * this method takes an sql decimal type and extracts 2 values from it 1
     * the highest integer allowed
     * and smallest decimal amount in the number
     * @param  string $data an string value that contains something like decimal(5,2)
     * @return array        an assoc array with 2 values
     *                      max which contains the highest integer value
     *                      and decimal which contains the lowest decimal value permitted
     */
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

    /**
     * this method takes an sql varchar type and extracts the max length allowed in it
     * @param  string $data an string value that contains something like varchar(5)
     * @return string       a numberic string which contains the max length
     */
    private function prepValidateVarchar($data) {
        $data = str_replace("varchar(", "", $data);
        $data = str_replace(")", "", $data);
        return $data;
    }

    /**
     * this method takes an sql char type and extracts/returns the required length
     * @param  string $data an string value that contains something like char(5)
     * @return string       a numberic string which contains the required length
     */
    private function prepValidateChar($data) {
        $data = str_replace("char(", "", $data);
        $data = str_replace(")", "", $data);
        return $data;
    }

    /**
     * this method takes an sql tinyint type and returns the max and min values allowed in it
     * @param  string $data expects 1 of 2 possible values "tinyint unsigned" or "tinyint"
     * @return array        returns an assoc array with 2 values min and max
     */
    private function prepValidateTinyInt($data) {
        if (strpos($data, 'unsigned') !== false){
            $max = 255;
            $min = 0;
        } else {
            $max = 	127;
            $min = -128;
        }

        return ['min' => $min,'max'=> $max];
    }

    /**
     * this method takes an sql smallint type and returns the max and min values allowed in it
     * @param  string $data expects 1 of 2 possible values "smallint unsigned" or "smallint"
     * @return array        returns an assoc array with 2 values min and max
     */
    private function prepValidateSmallInt($data) {
        if (strpos($data, 'unsigned') !== false){
            $max = 65535;
            $min = 0;
        } else {
            $max = 	32767;
            $min = -32768;
        }

        return ['min' => $min,'max'=> $max];
    }

    /**
     * this method takes an sql mediumint type and returns the max and min values allowed in it
     * @param  string $data expects 1 of 2 possible values "mediumint unsigned" or "mediumint"
     * @return array        returns an assoc array with 2 values min and max
     */
    private function prepValidateMediumInt($data) {
        if (strpos($data, 'unsigned') !== false){
            $max = 16777215;
            $min = 0;
        } else {
            $max = 	8388607;
            $min = -8388608;
        }

        return ['min' => $min,'max'=> $max];
    }

    /**
     * this method takes an sql int type and returns the max and min values allowed in it
     * @param  string $data expects 1 of 2 possible values "int unsigned" or "int"
     * @return array        returns an assoc array with 2 values min and max
     */
    private function prepValidateInt($data) {
        if (strpos($data, 'unsigned') !== false){
            $max = 4294967295;
            $min = 0;
        } else {
            $max = 	2147483647;
            $min = -2147483648;
        }

        return ['min' => $min,'max'=> $max];
    }

    /**
     * this method takes an sql bigint type and returns the max and min values allowed in it
     * @param  string $data expects 1 of 2 possible values "bigint unsigned" or "bigint"
     * @return array        returns an assoc array with 2 values min and max
     */
    private function prepValidateBigInt($data) {
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
