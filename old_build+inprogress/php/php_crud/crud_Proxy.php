<?php
if (isset($_POST['url'])) {
    $_POST['url'] = "files/" . $_POST['url'];
}

include('crud_Controller.php');


if (isset($createResult)) {
    $result = "<textarea name='content' rows='8' cols='40'></textarea>";
}

elseif (isset($readResult)) {
    $result = "<textarea name='content' rows='8' cols='40'>$readResult</textarea>";
}

elseif (isset($updateResult)) {
    // if at the setup step of the update
    if ($updateResult == "submit_update") {
        $result = "<textarea name='content' rows='8' cols='40'></textarea>";

    // else if at the finish step of the update
    } else {
        $result = "
            Update Data: <br>
            <textarea name='content' rows='6' cols='40'>$updateResult</textarea>
            <input type='submit' name='update' value='Update'>
            <input type='hidden' name='submit_update_url' value='$url'>
            <input type='hidden' name='submit_update'>
        ";
    }
}
elseif (isset($deleteResult)) {
    $result = "<textarea name='content' rows='8' cols='40'></textarea>";
}

//standard echo
else {
    $result = "<textarea name='content' rows='8' cols='40'></textarea>";
}

if (isset($_POST['create'] ) || isset($_POST['read'] ) || isset($_POST['update'] ) || isset($_POST['delete'] ) ) {
    echo $result;
}
