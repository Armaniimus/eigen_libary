<?php
require_once "traits/ValidatePHP_ID-v2.0.php";
class DataHandler {
    private $conn;
    public $error;
    public $lastInsertedID;
    public $dbName;
    private $tableData;

    private $PhpUtilities;

    public function __construct($dbName, $username, $pass, $serverAdress, $dbType) {
        $this->tableData = [];
        $this->dbName = $dbName;
        $this->conn = new PDO("$dbType:host=$serverAdress;dbname=$dbName", $username, $pass);

        $this->PhpUtilities = new PhpUtilities;

        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function __destruct() {
        $this->conn = NULL;
        $this->error = NULL;
        $this->lastInsertedID = NULL;
        $this->dbName = NULL;
        $this->tableData = NULL;
    }

    ####################
    # included trait
    ####################
    use ValidatePHP_ID;

    ######################
    # primary methods
    ######################

    /**
    * This method generates a sql query based on a tablename, columnNames and a dataArray.
    *
    * @param  string  $tableName            Sql Table Name
    * @param  array   $inputColumnNames     array with sqlcolumnnames you want to put data in
    * @param  array   $inputAssocArray      array with data you want to add to the db
    * @return string                        returns the generated generated sql create query
    */
    public function setCreateQuery($tableName, $inputColumnNames, $inputAssocArray) {

        // generate comma Seperated ColumnNames
        $sqlColumnNames = $this->generateSqlColumnNames($inputColumnNames);

        // generate Create record Data
        $recordData = $this->setRecordData_Assoc($inputColumnNames, $inputAssocArray, 1);

        // Combines $recordData, $tableName and $sqlColumnNames to create the SQL query
        $sql = "INSERT INTO $tableName ($sqlColumnNames)
        VALUES ($recordData)";

        return $sql;
    }

    /**
    * This method creates a new record in the database you have 2 options to do this task
    * Either with the given createQuery or with $tablename, $inputColumnNames, $inputAssocArray
    * after inserting the $this->lastInsertedID is set to potentialy use in a read method
    *
    * @param  string  $createQuery          Sql createQuery
    * @param  string  $tableName            Sql Table Name
    * @param  array   $inputColumnNames     array with sqlcolumnnames you want to put data in
    * @param  array   $inputAssocArray      array with data you want to add to the db
    * @return NULL                          none
    */
    public function createData($createQuery = NULL, $tableName = NULL, $inputColumnNames = NULL, $inputAssocArray = NULL) {
        // set the SQL Query if it isnt set
        if ($createQuery == NULL) {
            $createQuery = $this->setCreateQuery($tableName, $inputColumnNames, $inputAssocArray);
        }

        // try to add the record with pdo to the database
        $result = $this->runSqlQuery($createQuery);

        // Set lastInsertedID
        if ($result) {
            $this->lastInsertedID = $this->conn->lastInsertId();
        }
    }


    /**
    * This method can be used to get a multible rows from the database
    * this can be done with a simple read squery
    * but also with a preparedStatement
    *
    * @param  string  $readQuery            Sql readQuery
    * @param  array   $nrParamArray         an array with numbers which are injected in the sqlquery after the sqlserver has preparering the query
    * @return array                         returns a numberedArray with associative arrays in it with database data
    */
    public function readData($readQuery, $nrParamArray = NULL) {

        // If a prepared statement is needed because of evil user data
        if ($nrParamArray !== NULL) {
            $localConn = $this->handlePreparedStatement($readQuery, $nrParamArray);
            return $this->runSqlQuery(NULL, 1, $localConn);

        // Else just Run it
        } else {
            return $this->runSqlQuery($readQuery, 1);
        }
    }

    /**
    * This method can be used to get only a single row from the database
    * this can be done with a simple read squery
    * but also with a preparedStatement
    *
    * @param  string  $readQuery            Sql readQuery
    * @param  array   $nrParamArray         an array with numbers which are injected in the sqlquery after the sqlserver has preparering the query
    * @return array                         returns a associative array with database data
    */
    public function readSingleData($readQuery, $nrParamArray = NULL) {
        // If a prepared statement is needed because of evil user data
        if ($nrParamArray !== NULL) {
            $localConn = $this->handlePreparedStatement($readQuery, $nrParamArray);
            return $this->runSqlQuery(NULL, 2, $localConn);

        // Else just Run it
        } else {
            return $this->runSqlQuery($readQuery, 2);
        }
    }

    /**
    * This method is used to generate an updateQuery
    *
    * @param  string  $tablename            sql tablename
    * @param  array   $AssocArray           this is an array with the data to change
    * @param  string  $idName               this is the rows unique idName
    * @param  int     $idValue              this is the rows unique id number to select which row needs to change
    * @param  array   $inputColumnNames     this is an array with the collumns you want to change
    * @return string                        returns the generated update query
    */
    public function setUpdateQuery($tablename, $AssocArray, $idName = NULL, $idValue = NULL, $inputColumnNames = NULL) {

        # collumnNames collection + idName and Value collection;
            // get the $columnNames;
            if ($inputColumnNames == NULL) {
                $columnNames = $this->getColumnNames($tablename);
            } else {
                $columnNames = $inputColumnNames;
            }

            // set idName if not supplied
            if ($idName == NULL) {
                throw new Exception("UpdateQuery: IdName not supplied", 1);
                $idName = $columnNames[0];
            }

            // set idValue if not supplied
            if ($idValue == NULL) {
                throw new Exception("UpdateQuery: IdValue not supplied", 1);
                $idValue = $AssocArray[$idName];
            }

            // select the columnNames
            $columnNames = $this->PhpUtilities->selectWithCodeFromArray($columnNames, "02");
        # end of collumnNames collection + idName and Value collection

        // validate the ID and throw an error if appropiate
        $this->validatePHP_ID($idValue, "SetUpdateQuery");

        // collect the set part for the Query
        $set = $this->setRecordData_Assoc($columnNames, $AssocArray, 0);

        // set updateQuery
        $updateQuery = "UPDATE $tablename
        SET $set
        WHERE $idName = " . $idValue;

        return $updateQuery;
    }

    /**
    * This method is used to generate an updateQuery
    * after updating $this->lastInsertedID is set so it can be used in a readMethod
    * You can either use the $updateQuery or $tablename, $AssocArray, $idName, $idValue
    * to achieve the required result
    *
    * @param  string  $updateQuery          an sql updateQuery
    * @param  string  $tablename            sql tablename
    * @param  array   $AssocArray           this is an array with the data to change
    * @param  string  $idName               this is the rows unique idName
    * @param  int     $idValue              this is the rows unique id number to select which row needs to change
    * @return NULL                          none
    */
    public function updateData($updateQuery = NULL, $tableName = NULL, $AssocArray = NULL, $idName = NULL, $idValue = NULL) {

        if ($updateQuery == NULL) {
            if ($idValue == NULL || $idName == NULL) {
                throw new \Exception("Missing data to process the update request --[IdValue] --> $idValue  --[idName] -->$idName");
            }

            $updateQuery = $this->setUpdateQuery($tableName, $AssocArray, $idName, $idValue);
        }

        // run updateQuery
        $result = $this->runSqlQuery($updateQuery);

        if ($result && $idValue !== NULL) {
            $this->lastInsertedID = $idValue;
        }
    }

    /**
    * This method is used to generate an deleteQuery.
    *
    * @param  string  $tablename            sql tablename
    * @param  string  $idName               this is the rows unique idName
    * @param  int     $idValue              this is the rows unique id number to select which row needs to change
    * @return string                        returns an sql deleteQuery
    */
    public function setDeleteQuery($tablename, $idName, $idValue) {

        // Test if a valid id is provided and throw an error if appropiate
        $this->validatePHP_ID($idValue, "SetDeleteQuery");

        // set $deleteQuery
        $deleteQuery =
        "DELETE
        FROM $tablename
        WHERE $idName = $idValue";

        return $deleteQuery;
    }

    /**
    * This method is used to delete a row from the database
    *
    * @param  string  $deleteQuery          sql deleteQuery
    * @param  string  $tablename            sql tablename
    * @param  string  $idName               this is the rows unique idName
    * @param  int     $idValue              this is the rows unique id number to select which row needs to change
    * @return string                        returns an sql deleteQuery
    */
    public function deleteData($deleteQuery = NULL, $tablename = NULL, $idName = NULL, $idValue = NULL) {

        if ($deleteQuery == NULL) {
            $deleteQuery = $this->setDeleteQuery($tablename, $idName, $idValue);
        }

        return $this->runSqlQuery($deleteQuery);
    }

    ##################
    # helper methods
    ##################

    /**
    * This method is used by other readMethods to support prepared statements.
    *
    * @param  string  $readQuery           sql prepared readQuery
    * @param  array   $nrParamArray        array of values to use in the sql after the statement has been prepared in the db
    * @return array                        return an array of arrays with the selected data from the database
    */
    private function handlePreparedStatement($readQuery, $nrParamArray) {
        $localConn = $this->conn->prepare($readQuery);

        for ($i=0; $i < count($nrParamArray); $i++) {
            $localConn->bindParam($i+1, $nrParamArray[$i]);
        }
        $localConn->execute();

        return $localConn;
    }

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
    * This method is used to get tableData from the database like columnNames typeValues and nullValues
    * This data is stored in the class property $this->tableData[$GivenTableName]
    *
    * @param  string        $sqlQuery             a sqlquery
    * @param  string/int    $option               10 possible valid values
    *                                             (0, "create", "update", "delete") is used for any non read function
    *                                             (1, "readAll") used to select all selected rows from the db
    *                                             (2, "readSingle") used to select only the first row from the selected db rows
    *                                             (3, "readColumn") used to fetch only a singleColumn from the db
    *
    * @param  string        $receivedLocalConn    sql tableName
    * @return array/false   $returns false for non read functions otherwise returns an array
    */
    private function runSqlQuery($sqlQuery = NULL, $option = 0, $receivedLocalConn = NULL) {

        try {
            //SET local conn
            if ($sqlQuery !== NULL) {
                $localConn = $this->conn->prepare($sqlQuery);

            } else {
                $localConn = $receivedLocalConn;
            }

            // RUN Query non read functions
            // and return true or false for non read functions
            if ($option == 0 || $option == "create" || $option == "update" || $option == "delete") {
                if ( $localConn->execute() ) {
                    return TRUE;
                };

            // RUN Query read functions
            } else if ($sqlQuery !== NULL) {
                $localConn->execute();
            }

            // FETCH Assoc arrays
            if ($option == 1 || $option == "readAll") {
                $return = $localConn->fetchAll(PDO::FETCH_ASSOC);

            } else if ($option == 2 || $option == "readSingle") {
                $return = $localConn->fetch(PDO::FETCH_ASSOC);

            } else if ($option == 3|| $option == "readColumn") {
                $return = $localConn->fetchColumn();
            }
        }

        catch(PDOException $e) {
            echo "<pre>";
            echo "SQL: $sqlQuery";
                throw new Exception($e->getMessage());
            echo "</pre>";
            $return = false;
        }

        return $return;
    }

    /**
    * This method is used to generate the setPart in a updateQuery, or the Values part in an insert query
    *
    * @param  array        $colNames_nrArr       expects an array with columnNames which match the columnNames in the database
    * @param  array        $AssocDataArray       needs an associative dataArray that has uses the sameNames as supplied in colNames_nrArray
    * @param  string/int   $option               4 valid values (0, update) to let it generate a setpart or (1, create) to generate an create values part
    * @return string                             returns record data for the values part in an insert query or for an set part in an updateQuery
    */
    private function setRecordData_Assoc($colNames_nrArr, $AssocDataArray, $option) {

        // Generate Set part for the update
        if ($option == 0 || $option == 'update') {
            $recordData = $colNames_nrArr[0] . " = '" . $AssocDataArray[$colNames_nrArr[0]] . "'";
            for ($i=1; $i < count($colNames_nrArr); $i++) {

                // checks if supplied columnName exists as index in the data array
                if (isset($AssocDataArray[$colNames_nrArr[$i]])) {
                    $recordData .= ", " . $colNames_nrArr[$i] . " = '" . $AssocDataArray[$colNames_nrArr[$i]] . "'";
                }
            }
        }

        // Generate Values part for the update (not tested)
        else if ($option == 1 || $option == 'create') {
            $recordData = "'" . $AssocDataArray[$colNames_nrArr[0]] . "'";

            for ($i=1; $i < count($colNames_nrArr); $i++) {
                $recordData .= "," . "'" . $AssocDataArray[$colNames_nrArr[$i]] . "'";
            }
        }

        return $recordData;
    }

    /**
    * This method is used to convert columnNamesArray into a string of commaSeperatedValues
    *
    * @param  array        $colNames_nrArr       a array with columnNames which match the columnNames in the database
    * @return string                             a string of commaSeperatedValues
    */
    private function generateSqlColumnNames($Nr_Arr_ColNames) {
        //Generates $sqlColumnNames
        $sqlColumnNames = $Nr_Arr_ColNames[0];
        for ($i=1; $i<count($Nr_Arr_ColNames); $i++) {
            $sqlColumnNames .= "," . $Nr_Arr_ColNames[$i];
        }
        return $sqlColumnNames;
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
    public function globalsetTableTypes($tablename, $selectionCode = NULL) {
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
    public function getColumnNames($tablename, $selectionCode = NULL, $force = NULL) {

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

    ########################
    # secondary methods
    ########################
    /**
     * counts the amount results that are returned from the db
     * @param   string      $tablename  an sql table name
     * @param   string      $where      an valid sql where statement
     * @return  int                     returns the counted columns
     */
    public function countDataResults($tablename, $where = "") {
        $SearchQuery = "SELECT " . "count" . "(*) FROM $tablename $where";
        return $this->runSqlQuery($SearchQuery, 3);
    }

    /**
     * This method is used to generate a search query to search for 1 specified phrase or word in all columns
     * or to generate a more specified query of individual values per column
     * @param array/string   $whereData   an array of words to search for per column or 1 word to search for in each column
     * @param string         $tablename   a valid tablename from the sql server
     * @param array          $columnNames an array of columnnames that exist in the db
     * @param int            $option      used to define if an wheredata is a string or an array 0=array, 1=string
     */
    public function setSearchWhere($whereData, $tablename = NULL, $columnNames = NULL, $option = 1) {
        // get columnNames based on $tablename or $columnNames
        if ($columnNames == NULL && !empty($tablename) ) {
            $columnNames = $this->GetColumnNames($tablename);

        } else if ($tablename == NULL && $columnNames == NULL) {
            throw new Exception("No Useable parameters given");
        }
        $where = "";
        for ($i=0; $i<count($columnNames); $i++) {
            if ($option == 0) {
                $whereDataLoop = $whereData[$i];

            } else if ($option == 1) {
                $whereDataLoop = $whereData;

            } else {
                throw new Exception("wrong option selected in SetCreateWhere");
            }

            //if there is no data inside $selectdata then add nothing to the where statement.
            if ($whereDataLoop == "") {

            //else if there is Data inside $selectdata but no where statement yet then
            //(set the where statement and add the first condition)
            } else if ($whereDataLoop <> "" && $where == "") {
                $where = " WHERE " . $columnNames[$i] . ' LIKE "%' . $whereDataLoop . '%"';

            //else if there is data and an already existing where statement
            } else {
                $where .= " OR " . $columnNames[$i] . ' LIKE "%' . $whereDataLoop . '%"';
            }
        }
        return $where;
    }

    /**
     * this method can be used to generate a valid search query
     * in basis the tablename, wheredata and limit are required.
     *
     * but where data can be emtpy if you wish to swap use your own where in the where param
     * and if you wish to select fewer columnnames than the db has you can fill the columnNames param
     *
     * @param   string   $tablename   a tablename that exist in the db
     * @param   array    $whereData   a array with searchphrases per column in columnNames
     * @param   string   $limit       a string with nothing or with a valid sql limt phrase
     * @param   array    $columnNames a array with columnames that match the db
     * @param   string   $where       a valid sql where statement can be used as replacement for the generated where
     * @return  string                a sql select statement with an advanced where
     */
    public function setSearchQuery($tablename, $whereData, $limit, $columnNames = NULL, $where = NULL) {

        if ($columnNames == NULL) {
            $columnNames = $this->getColumnNames($tablename);
        }

        if ($where == NULL) {
            $where = $this->setSearchWhere($whereData, $tablename, $columnNames, 1);
        }

        $selectColNames = $this->generateSqlColumnNames($columnNames);

        $sql = "SELECT $selectColNames
        FROM $tablename
        $where
        $limit";

        return $sql;
    }

    /**
     * this method is used to generate pagination elements on the bottom of the page
     * also hrefs will be set in this pagination that add customizable Get variables at the end of it.
     * for styling names you can use 1 name with some -- extentions to it
     *
     * @param string $tablename        a valid sql tablename
     * @param int    $resAmountPerPage the required amount of result per page
     * @param string $where            requires a valid sql wherestatement
     * @param string $styleName        css stylename to be used as [stylename] [stylename]--start, [stylename]--end, [stylename]--bothends, [stylename]--Current
     * @param int    $currentPage      the number of the currentpage
     * @param string $optional         an extra amount of get values that can be added like "&view=contactpage"
     *
     * @return string                  the returned result is html which you can use to return to the user
     */
    public function createPagination($tablename, $resAmountPerPage, $where = "", $styleName, $currentPage = NULL, $optional = "") {
        $totalItems = $this->countDataResults($tablename, $where);

        // Set total pagination numbers
        $restItems = $totalItems % $resAmountPerPage;
        $totalPagination = floor($totalItems / $resAmountPerPage);

        if ($restItems > 0) {
            $totalPagination++;
        }

        // generateTable
        $forStart = 0;
        $pageTable = [];
        if ($currentPage > 1) {
            $pageCount = $currentPage-1;
            $pageTable[0] = "<a class='$styleName $styleName--start $styleName--bothEnds' href='index.php?page=$pageCount" . $optional . "'>&lt;&lt;</a>";

            $forStart++;
            $totalPagination++;
        }


        $pageCount = 1;
        $currentPageCheck = $currentPage;
        if ($forStart == 0) {
            $currentPageCheck--;
        }

        for ($i=$forStart; $i<$totalPagination; $i++) {
            if (($currentPageCheck) == ($i) ) {
                $pageTable[$i] = "<a class='$styleName--Current'>$pageCount</a>";
                $pageCount++;

            } else {
                $pageTable[$i] = "<a class='$styleName' href='index.php?page=$pageCount" . $optional . "'>$pageCount</a>";
                $pageCount++;
            }
        }

        if ($currentPage < $totalPagination-1) {
            $pageCount = 1+$currentPage;
            $pageTable[$i] = "<a class='$styleName $styleName--end $styleName--bothEnds' href='index.php?page=$pageCount" . $optional . "'>&gt;&gt;</a>";
        }

        return $pageTable;
    }
}
?>
