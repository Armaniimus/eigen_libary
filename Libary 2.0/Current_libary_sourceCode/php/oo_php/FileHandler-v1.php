<?php
    /**
     *
     */
    class FileHandler {

        private $mode;
        public $result = array("mode"=>"","content"=>"");

        public function __construct() {

        }

        public function create($url, $content) {
            $myfile = fopen($url, "w") or die("Unable to open file!");
            fwrite($myfile, $content);
            fclose($myfile);

            return $this->result;
        }

        public function read($url) {
            if (file_exists($url)) {
                $myfile = fopen($url, "r");

                // try to read the file;
                if (!(filesize($url) == false || filesize($url) == 0)) {
                    $result = fread($myfile, filesize($url));
                    fclose($myfile);
                }

                // if size = 0 but file exists return an empty string otherwise throw error;
                else {
                    // $this->result = ">>>ERROR: No data found file is empty<<<";
                    $this->result = "";
                }

            } else {
                $result = ">>>ERROR: No file found<<<";
            }

            return $result;
        }

        // logic needs to be in a controller
        public function update() {
            if (!isset($_POST['submit_update'] ) ) {
                $result = $this->read();
                return $this->result;

            } else {
                $result["content"] = $this->create($url, $content);
                $result["mode"] = "update_submit";
                return $result;
            }
        }

        public function delete() {
            unlink($url);
            return $result;
        }


    }
