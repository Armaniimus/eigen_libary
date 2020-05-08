<?php
    /**
     *  This class is used to provide support for other functions that require the datahandler
     *  This support includes column type information, columnName info, and column nullValues.
     */
    class DB_Support extends DB_Essentials {

        /**
        * This method is used to get tableData from the database like columnNames typeValues and nullValues
        * This data is stored in the class property $this->tableData[$GivenTableName]
        *
        * @param  string  $tableName        sql tableName
        * @return Null
        */
        private function setTableData($tablename) {
            // run Query
            $getDataQuery = "show Fields FROM $tablename";
            $queryRes = $this->runSqlQuery($getDataQuery, 1);

            // Set variables
            for ($i=0; $i<count($queryRes); $i++) {
                $this->tableData[$tablename]["columnNames"][$i] = $queryRes[$i]["Field"];
                $this->tableData[$tablename]["typeValues"][$i] = $queryRes[$i]["Type"];
                $this->tableData[$tablename]["nullValues"][$i] = $queryRes[$i]["Null"];
            }
        }

        /**
        * checks if typeValues are set in the class if so return them
        * if not get the typeValues from the database of a specified table and filter out results with the selectionCode
        * @param string $tablename         sql tableName
        * @param string $selectionCode     expects an string with the numbers 0123
        *                                  0 for don't get the data on this position
        *                                  1 for get the data on this position
        *                                  2 for get data on this position and all after it
        *                                  3 for dont get this data or any after it
        *
        * @return array                    an array of typevalues from the database
        */
        public function getTableTypes($tablename, $selectionCode = NULL) {
            if (!isset($this->tableData[$tablename]["typeValues"]) ) {
                $this->setTableData($tablename);
            }
            $data = $this->tableData[$tablename]["typeValues"];

            if ($selectionCode !== NULL) {
                $data = $this->selectWithCodeFromArray($data, $selectionCode);
            }

            return $data;
        }

        /**
        * checks if nullValues are set in the class if so return them
        * if not get the nullvalues from the database of a specified table and filter out results with the selectionCode
        * @param string $tablename         sql tableName
        * @param string $selectionCode     expects an string with the numbers 0123
        *                                  0 for don't get the data on this position
        *                                  1 for get the data on this position
        *                                  2 for get data on this position and all after it
        *                                  3 for dont get this data or any after it
        *
        * @return array                    an array of nullvalues from the database
        */
        public function getTableNullValues($tablename, $selectionCode = NULL) {
            if (!isset($this->tableData[$tablename]["nullValues"]) ) {
                $this->setTableData($tablename);
            }

            $data = $this->tableData[$tablename]["nullValues"];

            if ($selectionCode !== NULL) {
                $data = $this->selectWithCodeFromArray($data, $selectionCode);
            }

            return $data;
        }

        /**
        * checks if columnNames are set in the class if so return them
        * if not get the columnNames from the database of a specified table and filter out results with the selectionCode
        * @param string $tablename         sql tableName
        * @param string $selectionCode     expects an string with the numbers 0123
        *                                  0 for don't get the data on this position
        *                                  1 for get the data on this position
        *                                  2 for get data on this position and all after it
        *                                  3 for dont get this data or any after it
        *
        * @return array                    an array of columnNames from the database
        */
        public function getTableColumnNames($tablename, $selectionCode = NULL, $force = NULL) {

            $columnNamesAreSet = !isset($this->tableData[$tablename]["columnNames"]);
            if ($columnNamesAreSet || $force == 1) {
                $this->setTableData($tablename);
            }

            $columnNames = $this->tableData[$tablename]["columnNames"];

            if ($selectionCode !== NULL) {
                $columnNames = $this->selectWithCodeFromArray($columnNames, $selectionCode);
            }

            return $columnNames;
        }

        /**
        * This method is used to convert columnNamesArray into a string of commaSeperatedValues
        *
        * @param  array        $colNamesArray        a numbered array with columnNames which match the columnNames in the database
        * @return string                             a string of commaSeperatedValues
        */
        private function generateSqlColumnNames($colNamesArray) {
            //Generates $sqlColumnNames
            $sqlColumnNames = $colNamesArray[0];
            for ($i=1; $i<count($colNamesArray); $i++) {
                $sqlColumnNames .= "," . $colNamesArray[$i];
            }
            return $sqlColumnNames;
        }
    }
?>
