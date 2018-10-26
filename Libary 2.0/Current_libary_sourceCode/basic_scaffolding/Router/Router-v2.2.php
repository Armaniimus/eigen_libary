<?php
/**
 * routs the url to the correct controller
 */
class Router {

    // initialize vars
    Private $rootUrlStart;      /** @property int    */
    Private $packets;           /** @property array  packets taken raw from the url*/

    // core properties
    Private $controller;        /** @property object */
    Private $ctrlName;          /** @property string */
    Private $method;            /** @property string */
    Private $params;            /** @property array  all parameters for the controller*/

    // properties to be helpfull while debugging     
    Public  $filteredPackets;   /** @property array  packets that are sliced at the front*/
    Public  $error;             /** @property string */
    Public  $errorMessage;      /** @property string */

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
        $this->filteredPackets = array_slice($this->packets, $this->rootUrlStart);

        // Set error messages
        $this->error = NULL;
        $this->errorMessage = NULL;
    }

    /**
     * this method is used to kick off the start of the router and handle the errormessages
     * @return string/bool a string is returned or false is returned if there was an error
     */
    public function run() {
        //if no errors encountered return results
        if ($result = $this->determineDestination()) {
            return $result;
        }
    }

    /**
     * this method is used to get the destination information from the filtered packets and properties
     * and set it into the class then call the errorchecking Method.
     * if everything is oke then send it to the sendToDestination method.
     * @return string/bool if there was no error return the content from the controller
     */
    private function determineDestination() {
        $filteredPackets = $this->filteredPackets;
        $this->ctrlName  = array_shift($filteredPackets);
        $this->method    = array_shift($filteredPackets);
        $this->params    = $filteredPackets;

        // check if there are any errors
        if ($this->errorChecking()) {
            return $this->sendToDestination();
        } else {
            return FALSE;
        }
    }

    /**
     * this method is used to check if there are any errors that will prevent the router from functioning
     * if so set there in the class properties and return false if all checks are succesfull then true will be returned
     * also the controller propertie is set inside this method
     * @return bool     true, false
     */
    private function errorChecking() {
        $ctrlName = $this->ctrlName;
        $method   = $this->method;
        $params   = $this->params;

        // checks if the controllerName is given
        if ($ctrlName) {
            $ctrlNameFull = "Controller_$ctrlName";
        } else {
            $this->error = "E1";
            $this->errorMessage = "Controller param is missing and the request cant be fulfilled without it";
            return FALSE;
        }

        // check if controller file exists
        $ctrlPath = "Controller/$ctrlNameFull.php";
        if (file_exists ($ctrlPath) ) {
            require_once $ctrlPath;
        } else {
            $this->error = "E2";
            $this->errorMessage = "Controller file is missing";
            return FALSE;
        }

        // check if the controller class exists
        if (class_exists($ctrlNameFull)) {
            $this->controller = new $ctrlNameFull;
        } else {
            $this->error = "E3";
            $this->errorMessage = "Controller class is missing";
            return FALSE;
        }

        // check if method param is given
        // if not check if there is a default
        if (!$method) {
            if (!method_exists($this->controller, "default") ) {
                $this->error = "E4";
                $this->errorMessage = "no method was given and no default found";
                return FALSE;
            }
            $this->method = "default";
        }

        // checks if the method exists in the class
        // if not check if there is a default
        if (!method_exists($this->controller, $method) ) {
            if (!method_exists($this->controller, "default") ) {
                $this->error = "E5";
                $this->errorMessage = "method doesn't exist in the controller_class and no default found";
                return FALSE;
            }
            $this->method = "default";
        }

        return TRUE;
    }

    /**
     * this method is used to send the request to a the controller given in the parameter
     * and send the given info to it
     * @return string a return to be given back to index.php
     */
    private function sendToDestination() {
        $controller = $this->controller;
        $method     = $this->method;
        $params     = $this->params;

        // checks if params are defined and choose run mode based on that
        if (isset($params[0]) && $params[0]) {
            return $controller->$method($params);
        } else {
            return $controller->$method();
        }
    }
}
?>
