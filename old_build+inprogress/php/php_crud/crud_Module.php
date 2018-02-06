<?php
/******************
  Crud Controller
******************/
if (isset($_POST['create'] ) || isset($_POST['read'] ) || isset($_POST['update'] ) || isset($_POST['delete'] ) ) {

    //Setup Url
    function controlUrl($url) {
        $_POST['url'];
        if (isset( $_POST['submit_update'] ) ) {
            $url = $_POST['submit_update'];
        }
        $url = str_replace('..', '', $url);

        return $url;
    }
    $url = controlUrl($_POST['url']);


    function controlCrud($url, $content) {
        // controls the create
        if (isset($_POST['create'] ) ) {
            $content = new crud_Module($url, $content);
            $crudResult = $content->create();
        }

        // controls the read
        if (isset($_POST['read'] ) ) {
            $content = new crud_Module($url);
            $crudResult = $content->read();
        }

        // controls the update
        if (isset($_POST['update'] ) ) {
            $content = new crud_Module($url, $content);
            $crudResult = $content->update();
        }

        // controls the delete
        if (isset($_POST['delete'] ) ) {
            $content = new crud_Module($url);
            $crudResult = $content->delete();
        }

        return $crudResult;
    }
    $crudResult = controlCrud($url, $_POST['content']);
}
////

class crud_Module {
    private $url;
    private $content;
    private $mode;
    public $result = array("mode"=>"","content"=>"");

    public function __construct($url, $content = null) {
        $this->content = $content;
        $this->url = $url;
    }

    public function create() {
        $this->result["mode"] = "create";
        $myfile = fopen($this->url, "w") or die("Unable to open file!");
        fwrite($myfile, $this->content);
        fclose($myfile);

        return $this->result;
    }

    public function read() {
        $this->result["mode"] = "read";
        if (file_exists($this->url)) {
            $myfile = fopen($this->url, "r");

            // try to read the file;
            if (!(filesize($this->url) == false || filesize($this->url) == 0)) {
                $this->result["content"] = fread($myfile, filesize($this->url));
                fclose($myfile);
            }

            // if size = 0 but file exists return an empty string otherwise throw error;
            else {
                // $this->result = ">>>ERROR: No data found file is empty<<<";
                $this->result["content"] = "";
            }

        } else {
            $this->result["content"] = ">>>ERROR: No file found<<<";
        }

        return $this->result;
    }

    public function update() {
        if (!isset($_POST['submit_update'] ) ) {
            $this->result = $this->read();
            $this->result["mode"] = "update_setup";

            return $this->result;

        } else {
            $this->result["content"] = $this->create($this->url, $this->content);
            $this->result["mode"] = "update_submit";
            return $this->result;
        }
    }

    public function delete() {
        $this->result["mode"] = "delete";
        unlink($this->url);
        return $this->result;
    }
}

?>
