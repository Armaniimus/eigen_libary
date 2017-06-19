<?php
//-- version number 1.1 --//

//F01; D:none; S(G)
//Status: Good
//FunctionDescription:
    //Generate an Html select based on available data inside a array
//Variable input:
    //$dataArray(requires an 2dimensional Array with strings in them and 1 string)
    //$columnName(requires an string)
    //$openingLine requires a string with openingline with a select. //optional but handy if you want to control the type
function generateHtmlSelect($dataArray, $columnName, $openingLine = NULL) {

    //Generates a HTML Select Form element.
    if ($openingLine != NULL) {
        $result = $openingLine;
    } else {
        $result = "<select name='$columnName'>";
    }
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
