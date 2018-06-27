<?php

// $resultArray expects an 2DdataArray from a database
public function GenerateOverviewContent($resultArray, $buttons = NULL) {
    $href = 0;

    if (isset($_GET["view"])) {
        $view = $_GET["view"];
    } else
    $view = 'home';

    if ($buttons == NULL) {
        $href = 1;
        $buttons = "<a href='index.php?view=specific&id={id}' class='btn btn-primary'style='background-color: #1abc9c; border-color: #1abc9c;'>Bekijk product</a>
        <br>
        <br>
        <a href='index.php?view=$view&op=addToCart&id={id}' class='winkelwagen-knop pr-2 pl-2' style='background-color: #F1C40F; border-color: #F1C40F;'>Toevoegen aan winkelwagen</a>";
    }

    $contentBoxes = "";
    for ($i=0; $i < count($resultArray); $i++) {
        $contentBoxes .= "<div class='col mt-5'>
            <div class='card' style='width: 18rem;'>
                <a {href} style='height: 300px; padding: 15px;'>
                    <img style='max-height:290px; imagesize:contain;' class='card-img-top' src='" . $resultArray[$i]['afbeelding'] . "' alt='Card image cap'>
                </a>
                <div class='card-body'>
                    <div style='height: 75px'>
                       <h5 class='card-title'>" . $resultArray[$i]['naam'] . "</h5>
                    </div>
                    <p class='card-text'>" . $resultArray[$i]['prijs'] . "</p>
                    $buttons
                </div>
            </div>
        </div>";

        if ($href == 1) {
            $contentBoxes = str_Replace("{href}", "href='index.php?view=specific&id={id}'", $contentBoxes);
        } else {
            $contentBoxes = str_Replace("{href}", "", $contentBoxes);
        }

        $contentBoxes = str_Replace("{id}", $resultArray[$i]['id'], $contentBoxes);

    }
    return $contentBoxes;
}

public function GenerateAdminOverviewContent($resultArray) {

    if (isset($_POST['search'])) {
        $previousSearch = $_POST['search'];
    } else {
        $previousSearch = "";
    }

    $buttons = "<div>
        <form action='index.php?view=admin_update&id={id}' method='post'>
            <input class='wijzigproduct-knop' type='submit' name='multiversum' value='Wijzig Product informatie'/>
            <input type='hidden' name='search' value='$previousSearch'/>
        </form>
    </div>
    <br>
    <div style='padding-left: 15px;'>
        <div class='row'>
            <form action='index.php?view=admin_updatefoto&id={id}' method='post'>
                <input class='wijzigfoto-knop' type='submit' name='multiversum' value='Wijzig Foto'/>
                <input type='hidden' name='search' value='$previousSearch'/>
            </form>

            <form style='padding-left: 10px;' class='float-l' action='index.php?view=admin_delete&id={id}' method='post'>
                <input class='delete-knop' type='submit' name='multiversum' value='Delete Product'/>
                <input type='hidden' name='search' value='$previousSearch'/>
            </form>
        </div>
    </div>";
    return $this->FormatProducts($resultArray, $buttons);
}

?>
