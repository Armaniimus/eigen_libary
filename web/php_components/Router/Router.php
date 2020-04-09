<?php
/**
 *
 */
class Router {
    public $error;
    public $filteredUriArray;

    public function __construct ($rootUrl = "/") {
        // set packetOffset
        $uriOffset = count( explode("/", $rootUrl) );

        // getPayload
        $uri = $_SERVER['REQUEST_URI'];
        $uriArray = explode("/", $uri);

        $this->filteredUriArray = array_slice($uriArray, $uriOffset);
        $this->determineDestination($this->filteredUriArray);
    }

    /**
     * this method is used to get the destination information from the filtered packets and properties
     * and set it into the class then call the errorchecking Method.
     * if everything is oke then send it to the sendToDestination method.
     * @return void;
     */
    private function determineDestination($filteredUriArray) {
        $controller = array_shift($filteredUriArray);

        $ctrlName   = $controller . '_Controller';
        $ctrlPath   = "Controller/$ctrlName.php";
        $method     = array_shift($filteredUriArray);
        $params     = $filteredUriArray;

        $this->sendToDestination($ctrlName, $ctrlPath, $method, $params);
    }

    /**
     * this method is used to send the request to a the controller given in the parameter
     * and send the given info to it
     * @param  String $ctrlName
     * @param  String $ctrlPath
     * @param  String $method
     * @param  Array  $params
     * @return void
     */
    private function sendToDestination($ctrlName, $ctrlPath, $method, $params) {
        try {
            if ( file_exists($ctrlPath) ) {
                require_once "$ctrlPath";
            } else {
                throw new Error("File Doesn't exist", 1);
            }

            $controller = new $ctrlName();
            $controller->$method($params);

        } catch (Error $e) {
            $this->error = $e->getMessage();
        }
    }
}

?>
