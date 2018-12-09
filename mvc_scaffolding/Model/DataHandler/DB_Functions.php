<?php
    /**
     * This class provides functions that require the datahandler but are not strictly essential
     * and all the functions in this class provide no support for any other function that requires the datahandler
     * examples are pagination and exportToCSV
     */
    class DB_Functions extends DB_Support {

        public function countDataResults($tablename, $columnName = "*", $where = "") {
            $sql = "SELECT " . "count" . "($columnName) FROM $tablename $where";
            $sth = $this->PDO->query($sql);
            return $sth->fetchColumn();
        }

        /**
         * this method is used to generate pagination elements on the bottom of the page
         * also hrefs will be set in this pagination that add customizable Get variables at the end of it or router variables.
         * for styling names you can use 1 name with some -- extentions to it
         * @css class params for styling
         *  $styleName
         *  $styleName--start
         *  $styleName--end
         *  $styleName--bothEnds
         *  $styleName--current
         *
         *
         * @param string $tablename        a valid sql tablename
         * @param int    $resAmountPerPage the required amount of result per page
         * @param string $where            requires a valid sql wherestatement
         * @param string $styleName        css stylename to be used as [stylename] [stylename]--start, [stylename]--end, [stylename]--bothends, [stylename]--Current
         * @param string $endUrl           The start of the url until the $currentpage variable like $index.php?page= or mainController/
         * @param int    $currentPage      the number of the currentpage
         * @param string $endUrl           The end of an url after the $currentPage vaiabler like &othervar="something" or /something
         *
         * @return string                  the returned result is html which you can use to return to the user
         */
        public function createPagination($tablename, $resAmountPerPage, $where = "", $styleName, $currentPage = NULL, $urlEnd = "") {
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
                $pageTable[0] = "<a class='$styleName $styleName--start $styleName--bothEnds' href='" . $urlStart . $pageCount . $urlEnd . "'>&lt;&lt;</a>";

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
                    $pageTable[$i] = "<a class='$styleName' href='" . $urlStart . $pageCount . $urlEnd . "'>$pageCount</a>";
                    $pageCount++;
                }
            }

            if ($currentPage < $totalPagination-1) {
                $pageCount = 1+$currentPage;
                $pageTable[$i] = "<a class='$styleName $styleName--end $styleName--bothEnds' href='" . $urlStart . $pageCount . $urlEnd . "'>&gt;&gt;</a>";
            }

            return $pageTable;
        }

        /**
         * exports data to CSV
         *
         * @param array $data the data you want to export, must be 2d array
         * @return string $csv csv formatted data
         */
        public function exportToCSV(array $data) {

            function addQuotes($val) {
                return "\"$val\"";
            }

            $csv = "";
            foreach ($data as $value) {
                $csv .= implode(", ", array_map("addQuotes", array_keys($value))) . "\r\n";
                break;
            }

            foreach ($data as $value) {
                $csv .= implode(", ", array_map("addQuotes", array_values($value))) . "\r\n";
            }

            return $csv;
        }
    }

?>
