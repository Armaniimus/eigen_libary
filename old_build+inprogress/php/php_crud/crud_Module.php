<?php
/******************
  Crud Controller
******************/
if (isset($_POST['create'] ) || isset($_POST['read'] ) || isset($_POST['update'] ) || isset($_POST['delete'] ) ) {

    function controlUrl($url) {
        $_POST['url'];
        if (isset( $_POST['submit_update_url'] ) ) {
            $url = $_POST['submit_update_url'];
        }
        $url = str_replace('..', '', $url);

        return $url;
    }

    $url = controlUrl($_POST['url']);

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
////

class crud_Module {
    private $url;
    private $content;
    private $mode;
    public $result;

    public function __construct($url, $content = null) {
        $this->content = $content;
        $this->url = $url;
    }

    public function create() {
        $myfile = fopen($this->url, "w") or die("Unable to open file!");
        fwrite($myfile, $this->content);
        fclose($myfile);

        return "submit_create";
    }

    public function read() {

        if (file_exists($this->url)) {
            $myfile = fopen($this->url, "r");

            // try to read the file;
            if (!(filesize($this->url) == false || filesize($this->url) == 0)) {
                $this->result = fread($myfile, filesize($this->url));
                fclose($myfile);
            }

            // if size = 0 but file exists return an empty string otherwise throw error;
            else {
                // $this->result = ">>>ERROR: No data found file is empty<<<";
                $this->result = "";
            }

        } else {
            $this->result = ">>>ERROR: No file found<<<";
        }

        return $this->result;
    }

    public function update() {
        if (isset($_POST['submit_update'] ) ) {
            $this->create($this->url, $this->content);
            return "submit_update";

        } else {
            return $this->read();
        }
    }

    public function delete() {
        unlink($this->url);
        return "submit_delete";
    }
}

?>
