<?php
//-- version number 0.9 --//

//F01; D:none; S(999)
//Status: Untested
//FunctionDescription:
    //Generate an Html select based on available data inside a array
//Variable input:
    //$dataArray(requires an associative Array with strings in them and 1 string) !!required!!
    //$columnName(requires an string) !!required!!
    //$selected(requires an int and its used to select the index from the Array for the selected option) !!(optional)!!
function generateHtmlSelectFromArray($array, $selectNameAndId, $selected = null) {

    //Generates a HTML Select Form element.
    $result = "<select name='$selectNameAndId' id='$selectNameAndId'>";
    for ($i=0; $i < count($array); $i++) {
        if ($i == $selected) {
            $result .= "<option value='" . $array[$i] . "' selected>" . $array[$i] . "</option>";
        } else {
            $result .= "<option value='" . $array[$i] . "'>" . $array[$i] . "</option>";
        }
    }
    $result .= "</select>";

    return $result;
}
?>
