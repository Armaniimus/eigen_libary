<?php

class crud_Module {
    private $url;
    private $content;
    private $mode;
    public $result;

    public function __construct($url, $content = null) {
        //if update is submitted set submitted url
        if (isset( $_POST['submit_update_url'] ) ) {
            $url = $_POST['submit_update_url'];
        }

        $url = str_replace('..', '', $url);
        $this->url = $url;
        $this->content = $content;
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
                fclose($myfile);
            }

        } else {
            $this->result = ">>>ERROR: No file found<<<";
        }

        return $this->result;
    }

    public function update($fase) {
        if ($fase == 0 || $fase == "setup") {
            return $this->read();
        }

        else if ($fase == 1 || $fase == "submit") {
            $this->create($this->url, $this->content);
            return "submit_update";
        }
    }

    public function delete() {
        unlink($this->url);
        return "submit_delete";
    }
}

?>
