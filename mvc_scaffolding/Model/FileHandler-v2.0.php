<?php
    /**
     *  
     */
    class FileHandler {

        private $mode;
        public $result;
        public $error = false;
        public $errorMessage = false;

        public function __construct() {

        }

        /**
         * this method is used to create a file with the given content on the given place.
         * @param  string $url     a string with a valid local url
         * @param  string $content a string with the content to be saved
         * @return bool            true, false
         */
        public function create($url, $content) {
            $myfile = fopen($url, "w") or die("Unable to open file!");
            fwrite($myfile, $content);
            fclose($myfile);

            return true;
        }

        /**
         * this method is used to read the contents of a file
         * @param  string $url     a string with a valid local url
         * @return string          returns the content of the file or the bool false
         */
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
                $result = false;
            }

            return $result;
        }

        // logic needs to be in a controller
        public function update($content, $url, $mode = 'read') {
            if ($mode == 'read') {
                $result = $this->read();
                return $this->result;

            } else if ($mode == 'update') {
                return $this->create($url, $content);

            } else {
                $this->error = true;
                $this->errorMessage = "invalid parameter given";
            }
        }

        /**
         * this method is used to delete a file
         * @param  string $url     a string with a valid local url
         * @return bool            true if no error was found else its false
         */
        public function delete($url) {
            if (file_exists($url)) {
                unlink($url);
                return TRUE;

            } else {
                return FALSE;
            }
        }
    }
?>
