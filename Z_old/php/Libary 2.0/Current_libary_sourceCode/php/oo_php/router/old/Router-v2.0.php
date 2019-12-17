<?php
/**
 * routs the url to the correct controller
 */
class Router {

    // initialize vars
    Private $rootUrlStart;
    Private $packets;
    Public $filteredPackets;
    Public $error;
    Public $errorMessage;

    /**
     * in this constructor the packets property is set and the data for it is gained from the browser URI
     * and this is filtered with the gived rootUrlStart
     * for example if you have a folder like pc/c/hi/rootprojectcontrol/method/param1/param2
     * a rootUrlStart of 4 will result in control/method/param1/param2 which you want in this way
     * @param integer $rootUrlStart this property is used to determine the offset of the folder
     */
    function __construct ($rootUrlStart = 0) {
        // set urlOffset
        $this->rootUrlStart = $rootUrlStart;

        // getPayload
        $url = $_SERVER['REQUEST_URI'];
        $this->packets = explode("/", $url);

        $this->filteredPackets = $this->getFilterPackets();

        // Set error messages
        $this->error = NULL;
        $this->errorMessage = NULL;
    }

    /**
     * this method is used to kick off the start of the router and handle the errormessages
     * @return string/bool a string is returned or false is returned if there was an error
     */
    public function run() {
        $result = $this->determineDestination();

        if ($result == "E1") {
            $this->error = "E1";
            $this->errorMessage = "Controller file isn't found";
            return FALSE;

        } else if ($result == "E2") {
            $this->error = "E2";
            $this->errorMessage = "no method was given and no default found";
            return FALSE;

        } else if ($result == "E3") {
            $this->error = "E3";
            $this->errorMessage = "controller_class isn't found";
            return FALSE;


        } else if ($result == "E4") {
            $this->error = "E4";
            $this->errorMessage = "method doesn't exist in the controller_class";
            return FALSE;

        } else {
            return $result;
        }
    }

    /**
     * this method is used to filter the packets in the class based on the rootUrlStartNumber
     * and can also be used to be able to find errors
     * @return array this is the filtered array
     */
    public function getFilterPackets() {
        return array_slice($this->packets, $this->rootUrlStart);
    }

    /**
     * this method is used to get the destination information from the filtered packets and properties
     * and pass it on the the send to destinationMethod
     * @return string  a string to be returned to index.php
     */
    private function determineDestination() {
        $filteredPackets = $this->filteredPackets;
        $ctrlName = array_shift($filteredPackets);
        $method = array_shift($filteredPackets);

        // set up the name and path
        $ctrlNameFull = "Controller_$ctrlName";
        $ctrlPath = "Controller/$ctrlNameFull.php";

        // check if destination exists
        if (file_exists ($ctrlPath) ) {
            require_once $ctrlPath;
            return $this->sendToDestination($ctrlNameFull, $method, $filteredPackets);

        } else {
            return "E1";
        }
    }

    /**
     * this method is used to send the request to a the controller given in the parameter
     * and send the given info to it
     *
     * @param  string $ctrlName  a controllerName
     * @param  string $method    a methodName
     * @param  array  $params    a array with params inside it
     * @return string            a return to be given back to index.php
     */
    public function sendToDestination($ctrlName, $method, $params) {
        //setup the params and run the controller
        // if (isset($method) && $method) {
        //     if (isset($params[0]) && $params[0]) {
        //         $controller = new $ctrlName($method, $params);
        //     } else {
        //         $controller = new $ctrlName($method);
        //     }
        // } else {
        //     return "E2";
        // }
        // return $controller->runController();

        // checks if the method name is defined
        if (!isset($method) || !$method) {
            return "E2";
        }

        // checks if the class exists
        if (class_exists($ctrlName)) {
            $controller = new $ctrlName;
        } else {
            return "E3";
        }

        // checks if the method exists in the class
        if (!method_exists($controller, $method) ) {
            if (!method_exists($controller, "default") ) {
                return "E4";
            } else {
                $method = "default";
            }
        }

        // checks if params are defined and choose run mode based on that
        if (isset($params[0]) && $params[0]) {
            return $controller->$method($params);
        } else {
            return $controller->$method();
        }
    }
}
?>
