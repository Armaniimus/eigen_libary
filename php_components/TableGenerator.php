<?php
/**
 * this class is used to create htmlElements from a given set of data
 * @method createHtmlTable
 */
Class HtmlElements {
    /**
     * This array creates an htmlTable from an array of assocArrays.
     * @param  Array    $array requires an array of assocArrays.
     * @return String   returns htmlTable
     */
    public function createHtmlTable($array, $name = "") {
        $tHead = '';
        $tBody = '';
        $row = '';

        //createHead
        foreach ($array as $value) {
            $row = '';
            foreach ($value as $k => $v) {
                $row .= "<th>$k</th>";
            }
            $tHead .= "<tr>$row</tr>";
            break;
        }
        $tHead = "<thead>$tHead</thead>";

        //createBody
        foreach ($array as $value) {
            $row = '';
            foreach ($value as $k => $v) {
                $row .= "<td>$v</td>";
            }
            $tBody .= "<tr>$row</tr>";
        }
        $tBody = "<tbody>$tBody</tbody>";

        return "<table class='table--$name'>$tHead $tBody</table>";
    }
}

?>
