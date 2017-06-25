<?php
//-- version number 1.0 --//

//F01; D:none; S(G)
//Status: Good
//FunctionDescription:
    //Generate an Html select based on available data inside a array
//Variable input:
    //$dataArray(requires an 2dimensional Array with strings in them and 1 string)
    //$columnName(requires an string)
function generateHtmlSelect($dataArray, $columnName) {

    //Generates a HTML Select Form element.
    $result =
    "<select onchange='submit()' id='select' name='$columnName' placeholder='$columnName'>";
    foreach ($dataArray as $dA) {
        if (isset($_POST[$columnName]) && $_POST[$columnName] == $dA) {
            $result .= "<option value='$dA' selected>$dA</option>";
        } else {
            $result .= "<option value='$dA'>$dA</option>";
        }
    }
    $result .= "</select>";

    return $result;
}
?>
