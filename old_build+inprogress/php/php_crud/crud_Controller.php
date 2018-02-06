<?php
if (isset($_POST['create'] ) || isset($_POST['read'] ) || isset($_POST['update'] ) || isset($_POST['delete'] ) ) {

    //set the url
    $url = $_POST['url'];

    //includes the crud_Module class
    include('crud_Module.php');

    // controls the create
    if (isset($_POST['create'] ) ) {
        $content = new crud_Module($url, $_POST['content']);
        $createResult = $content->create();
        //
        // if ($createResult == "submit_create") {
        //     $result = "<textarea name='content' rows='8' cols='40'></textarea>";
        // }
    }

    // controls the read
    if (isset($_POST['read'] ) ) {
        $content = new crud_Module($url);
        $readResult = $content->read();

        // $result = "<textarea name='content' rows='8' cols='40'>$readResult</textarea>";
    }

    // controls the update
    if (isset($_POST['update'] ) ) {
        $content = new crud_Module($url, $_POST['content']);
        $updateResult = $content->update();

        // // if at the setup step of the update
        // if ($updateResult == "submit_update") {
        //     $result = "<textarea name='content' rows='8' cols='40'></textarea>";
        //
        // // else if at the finish step of the update
        // } else {
        //     $result = "
        //         Update Data: <br>
        //         <textarea name='content' rows='6' cols='40'>$updateResult</textarea>
        //         <input type='submit' name='update' value='Update'>
        //         <input type='hidden' name='submit_update_url' value='$url'>
        //         <input type='hidden' name='submit_update'>
        //     ";
        // }
    }

    // controls the delete
    if (isset($_POST['delete'] ) ) {
        $content = new crud_Module($url);
        $deleteResult = $content->delete();

        // $result = "<textarea name='content' rows='8' cols='40'></textarea>";
    }
    // echo $result;

} else {
    echo "<textarea name='content' rows='8' cols='40'></textarea>";
}
?>
