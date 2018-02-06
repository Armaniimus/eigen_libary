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
    }

    // controls the read
    if (isset($_POST['read'] ) ) {
        $content = new crud_Module($url);
        $readResult = $content->read();
    }

    // controls the update
    if (isset($_POST['update'] ) ) {
        $content = new crud_Module($url, $_POST['content']);
        $updateResult = $content->update();
    }

    // controls the delete
    if (isset($_POST['delete'] ) ) {
        $content = new crud_Module($url);
        $deleteResult = $content->delete();
    }
}
?>
