<?php

// if valid request is send
// if (isset($_POST['url']) && $_POST['url'] !== "") {
if (isset($_POST['url']) ) {

    $_POST['url'] = "files/" . $_POST['url'];
    include('crud_Controller.php');


    // Handle crud events
    if (isset($createResult)) {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }

    else if (isset($readResult)) {
        $result = "<textarea name='content' rows='8' cols='40'>$readResult</textarea>";
    }

    else if (isset($updateResult)) {
        // if at the setup step of the update
        if ($updateResult == "submit_update") {
            $result = "<textarea name='content' rows='8' cols='40'></textarea>";

        // else if at the finish step of the update
        } else {
            $result = "
                Update Data: <br>
                <textarea name='content' rows='6' cols='40'>$updateResult</textarea>
                <input type='submit' name='update' value='update'>
                <input type='hidden' name='submit_update_url' value='$url'>
                <input type='hidden' name='submit_update'>
            ";
        }
    }
    else if (isset($deleteResult)) {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }
}

if (!isset($result)) {
    $result = "<textarea name='content' rows='8' cols='40'></textarea>";
}
echo $result;
