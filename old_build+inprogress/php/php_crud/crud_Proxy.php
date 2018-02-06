<?php
if ( isset($_POST['url']) && $_POST['url'] !== "") {
    $_POST['url'] = "files/" . $_POST['url'];
}

include('crud_Module.php');


if (isset($crudResult)) {
    if ($crudResult["mode"] == "create") {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }

    else if ($crudResult["mode"] == "read") {
        $result = "<textarea name='content' rows='8' cols='40'>" . $crudResult["content"] . "</textarea>";
    }

    else if ($crudResult["mode"] == "update_setup") {
        $result = "
            Update Data: <br>
            <textarea name='content' rows='6' cols='40'>" . $crudResult["content"] . "</textarea>
            <input type='submit' name='update' value='Update'>
            <input type='hidden' name='submit_update' value='$url'>
        ";
    }

    else if ($crudResult["mode"] == "update_submit") {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }

    else if ($crudResult["mode"] == "delete") {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }
}

if (!isset($result) ) {
    $result = "<textarea name='content' rows='8' cols='40'></textarea>";
}

echo $result;
