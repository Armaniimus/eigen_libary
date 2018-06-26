<?php
    require_once "traits\ValidatePHP_ID.php";
    require_once "PhpUtilities-v2.php";
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
        public function SetCreateQuery($tableName, $inputColumnNames, $inputAssocArray) {

            // generate comma Seperated ColumnNames
            $sqlColumnNames = $this->GenerateSqlColumnNames($inputColumnNames);

            // generate Create record Data
            $recordData = $this->SetRecordData_Assoc($inputColumnNames, $inputAssocArray, 1);

            // Combines $recordData, $tableName and $sqlColumnNames to create the SQL query
            $sql = "INSERT INTO $tableName ($sqlColumnNames)
            VALUES ($recordData)";

            return $sql;
        }

        // supply $sqlQuery or ($tablename + $inputColumnNames + $inputAssocArray);
        public function CreateData($createQuery = NULL, $tableName = NULL, $inputColumnNames = NULL, $inputAssocArray = NULL) {
            // set the SQL Query if it isnt set
            if ($createQuery == NULL) {
                $createQuery = $this->SetCreateQuery($tableName, $inputColumnNames, $inputAssocArray);
            }

            // try to add the record with pdo to the database
            $result = $this->RunSqlQuery($createQuery);

            // Set lastInsertedID
            if ($result) {
                $this->lastInsertedID = $this->conn->lastInsertId();
            }
        }

        public function ReadData($readQuery, $nrParamArray = NULL) {

            // If a prepared statement is needed because of evil user data
            if ($nrParamArray !== NULL) {
                $localConn = $this->HandlePreparedStatement($readQuery, $nrParamArray);
                return $this->RunSqlQuery(NULL, 1, $localConn);

            // Else just Run it
            } else {
                return $this->RunSqlQuery($readQuery, 1);
            }
        }

        public function ReadSingleData($readQuery, $nrParamArray = NULL) {
            // If a prepared statement is needed because of evil user data
            if ($nrParamArray !== NULL) {
                $localConn = $this->HandlePreparedStatement($readQuery, $nrParamArray);
                return $this->RunSqlQuery(NULL, 2, $localConn);

            // Else just Run it
            } else {
                return $this->RunSqlQuery($readQuery, 2);
            }
        }

        public function SetUpdateQuery($tablename, $AssocArray, $idName = NULL, $idValue = NULL, $inputColumnNames = NULL) {

            # collumnNames collection + idName and Value collection;
                // get the $columnNames;
                if ($inputColumnNames == NULL) {
                    $columnNames = $this->GetColumnNames($tablename);
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
                $columnNames = $this->PhpUtilities->SelectWithCodeFromArray($columnNames, "02");
            # end of collumnNames collection + idName and Value collection

            // validate the ID and throw an error if appropiate
            $this->ValidatePHP_ID($idValue, "SetUpdateQuery");

            // collect the set part for the Query
            $set = $this->SetRecordData_Assoc($columnNames, $AssocArray, 0);

            // set updateQuery
            $updateQuery = "UPDATE $tablename
            SET $set
            WHERE $idName = " . $idValue;

            return $updateQuery;
        }

        // requires ($updateQuery) or ($tableName + $AssocArray + $idValue + $idName)

        // string variables -> $updateQuery $tableName $idName
        // int variables -> $idValue
        // array variables -> $AssocArray
        public function UpdateData($updateQuery = NULL, $tableName = NULL, $AssocArray = NULL, $idName = NULL, $idValue = NULL) {

            if ($updateQuery == NULL) {
                if ($idValue == NULL || $idName == NULL) {
                    throw new \Exception("Missing data to process the update request --[IdValue] --> $idValue  --[idName] -->$idName");
                }

                $updateQuery = $this->SetUpdateQuery($tableName, $AssocArray, $idName, $idValue);
            }

            // run updateQuery
            $result = $this->RunSqlQuery($updateQuery);

            if ($result && $idValue !== NULL) {
                $this->lastInsertedID = $idValue;
            }
        }

        // requires every parameter
        public function SetDeleteQuery($tablename, $idName, $idValue) {

            // Test if a valid id is provided and throw an error if appropiate
            $this->ValidatePHP_ID($idValue, "SetDeleteQuery");

            // set $deleteQuery
            $deleteQuery =
            "DELETE
            FROM $tablename
            WHERE $idName = $idValue";

            return $deleteQuery;
        }

        // requires ($deleteQuery) or ($tablename + $idName + $idValue)
        public function DeleteData($deleteQuery = NULL, $tablename = NULL, $idName = NULL, $idValue = NULL) {

            if ($deleteQuery == NULL) {
                $deleteQuery = $this->SetDeleteQuery($tablename, $idName, $idValue);
            }

            return $this->RunSqlQuery($deleteQuery);
        }

        ##################
        # helper methods
        ##################
        private function HandlePreparedStatement($readQuery, $nrParamArray) {
            $localConn = $this->conn->prepare($readQuery);

            for ($i=0; $i < count($nrParamArray); $i++) {
                $localConn->bindParam($i+1, $nrParamArray[$i]);
            }
            $localConn->execute();

            return $localConn;
        }

        /****
        ** description -> Gets critical tabledata
        ** relies on methods -> RunSqlQuery()

        ** Requires -> $tablename
        ** string variables -> $tablename
        ****/
        private function SetTableData($tablename) {
            // run Query
            $getDataQuery = "show Fields FROM $tablename";
            $queryRes = $this->RunSqlQuery($getDataQuery, 1);

            // Set variables
            for ($i=0; $i<count($queryRes); $i++) {
                $this->tableData[$tablename]["columnNames"][$i] = $queryRes[$i]["Field"];
                $this->tableData[$tablename]["typeValues"][$i] = $queryRes[$i]["Type"];
                $this->tableData[$tablename]["nullValues"][$i] = $queryRes[$i]["Null"];
            }
        }

        /****
        ** description -> run a pdo database query
        ** relies on methods -> Null

        ** Requires -> $sqlQuery $option
        ** string variables -> $sqlQuery
        ** int variables -> $option
        ****/
        private function RunSqlQuery($sqlQuery = NULL, $option = 0, $receivedLocalConn = NULL) {

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

        /****
        ** description -> Sets insert data for the create query or set data for the updateQuery
        ** relies on methods -> Null

        ** Requires -> $colNames_nrArr, $AssocArray, $option
        ** assocArray variables -> $AssocArray
        ** nrArray variables -> $colNames_nrArr
        ** int variables -> $option
        ****/
        private function SetRecordData_Assoc($colNames_nrArr, $AssocArray, $option) {

            // Generate Set part for the update
            if ($option == 0 || $option == 'update') {
                $recordData = $colNames_nrArr[0] . " = '" . $AssocArray[$colNames_nrArr[0]] . "'";
                for ($i=1; $i < count($colNames_nrArr); $i++) {
                    if (isset($AssocArray[$colNames_nrArr[$i]])) {
                        $recordData .= ", " . $colNames_nrArr[$i] . " = '" . $AssocArray[$colNames_nrArr[$i]] . "'";
                    }
                }
            }

            // Generate Values part for the update (not tested)
            else if ($option == 1 || $option == 'create') {
                $recordData = "'" . $AssocArray[$colNames_nrArr[0]] . "'";

                for ($i=1; $i < count($colNames_nrArr); $i++) {
                    $recordData .= "," . "'" . $AssocArray[$colNames_nrArr[$i]] . "'";
                }
            }

            return $recordData;
        }

        /****
        ** description -> sets the column names for the SELECT or UPDATE part in a query
        ** relies on methods -> Null

        ** Requires -> $Nr_Arr_ColNames
        ** nrArray variables -> $Nr_Arr_ColNames
        ****/
        private function GenerateSqlColumnNames($Nr_Arr_ColNames) {
            //Generates $sqlColumnNames
            $sqlColumnNames = $Nr_Arr_ColNames[0];
            for ($i=1; $i<count($Nr_Arr_ColNames); $i++) {
                $sqlColumnNames .= "," . $Nr_Arr_ColNames[$i];
            }
            return $sqlColumnNames;
        }

        /****
        ** description -> Gets tableTypes from the database
        ** relies on methods -> SetTableData() SelectWithCodeFromArray()

        ** Requires -> $tablename, $option
        ** Optional -> $selectionCode -> used to select only certaint fields from the array
        ** string variables -> $tablename $selectionCode
        ** int variables -> $option
        **
        ** global variables -> tableData[$tablename][typeValues] -> this gets set by SetTableData if not set allready
        ****/
        public function GetTableTypes($tablename, $selectionCode = NULL) {
            if (!isset($this->tableData[$tablename]["typeValues"]) ) {
                $this->SetTableData($tablename);
            }
            $data = $this->tableData[$tablename]["typeValues"];

            if ($selectionCode !== NULL) {
                $data = $this->SelectWithCodeFromArray($data, $selectionCode);
            }

            return $data;
        }

        /****
        ** description -> Gets from the database what fields cannot be null
        ** relies on methods -> SetTableData() SelectWithCodeFromArray()

        ** Requires -> $tablename, $option
        ** Optional -> $selectionCode -> used to select only certaint fields from the array
        ** string variables -> $tablename $selectionCode
        ** int variables -> $option
        **
        ** global variables -> tableData[$tablename][typeValues] -> this gets set by SetTableData if not set allready
        ****/
        public function GetTableNullValues($tablename, $selectionCode = NULL) {
            if (!isset($this->tableData[$tablename]["nullValues"]) ) {
                $this->SetTableData($tablename);
            }

            $data = $this->tableData[$tablename]["nullValues"];

            if ($selectionCode !== NULL) {
                $data = $this->SelectWithCodeFromArray($data, $selectionCode);
            }

            return $data;
        }


        /****
        ** description -> Gets tableTypes from the database
        ** relies on methods -> SetTableData() SelectWithCodeFromArray()

        ** Requires -> $tablename, $option
        ** Optional -> $selectionCode -> used to select only certaint fields from the array
        ** string variables -> $tablename $selectionCode
        ** int variables -> $option
        **
        ** global variables -> tableData[$tablename][typeValues] -> this gets set by SetTableData if not set allready
        ****/
        public function GetColumnNames($tablename, $selectionCode = NULL, $force = NULL) {

            $columnNamesAreSet = !isset($this->tableData[$tablename]["columnNames"]);
            if ($columnNamesAreSet || $force == 1) {
                $this->SetTableData($tablename);
            }

            $columnNames = $this->tableData[$tablename]["columnNames"];

            if ($selectionCode !== NULL) {
                $columnNames = $this->SelectWithCodeFromArray($columnNames, $selectionCode);
            }

            return $columnNames;
        }

        ########################
        # secondary methods
        ########################
        public function CountDataResults($tablename, $where = "") {
            $SearchQuery = "SELECT " . "count" . "(*) FROM $tablename $where";
            return $this->RunSqlQuery($SearchQuery, 3);
        }

        // requires numbered arrays
        public function SetSearchWhere($whereData, $tablename = NULL, $columnNames = NULL, $option = 1) {

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

        // $tablename requires a string
        // $wheredate requires a numbered array
        // $columnNames requires a numbered array but is optional
        public function SetSearchQuery($tablename, $whereData, $limit, $columnNames = NULL, $where = NULL) {

            if ($columnNames == NULL) {
                $columnNames = $this->GetColumnNames($tablename);
            }

            if ($where == NULL) {
                $where = $this->SetSearchWhere($whereData, $tablename, $columnNames, 1);
            }

            $selectColNames = $this->GenerateSqlColumnNames($columnNames);

            $sql = "SELECT $selectColNames
            FROM $tablename
            $where
            $limit";

            return $sql;
        }

        public function CreatePagination($tablename, $resAmountPerPage, $where = "", $styleName, $currentPage = NULL, $optional = "") {
            $totalItems = $this->CountDataResults($tablename, $where);

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
