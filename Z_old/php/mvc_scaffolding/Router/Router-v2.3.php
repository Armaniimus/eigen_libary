<?php
/**
 * @description
 * The purpose of this class is to rout the incoming traffic to the right controller.
 * for this purpose you need to supply the uri with the controllername and methodname you can also send parameter if neccesary.
 * example www.mywebsite.com/controllername/methodname/param1/param2
 *
 * @Requirements
 * the router requires that all controllers have "Controller_" in front of the name of the file and the class
 * Also the default method needs to be mydefault
 *
 * @first_fallback
 * the first fallback is the mydefault method if methodname isn't given or doesn't exist
 * it still depends on a given controllername in the uri
 *
 * @second fallback
 * as secondary fallback a class can be used.
 * this is specified with the 2nd param of this class.
 * this fallback always uses the mydefault method
 */
class Router {

    // initialize vars
    Private $rootUrl;           /** @property int    */
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
    Public  $fallBackCtrl;      /** @property string this string needs to be the unique name part of the controller like main in Controller_main.php*/

    /**
     * in this constructor the packets property is set and the data for it is gained from the browser URI
     * and this is filtered with the gived rootUrl
     * for example if you have a folder like c:/folder/folder/rootproject/control/method/param1/param2
     * a rootUrl of 4 will result in control/method/param1/param2 which you want in this way
     * @param integer $rootUrl      this property is used to determine the offset of the folder
     * @param string  $fallBackCtrl (optional) this string needs to be the unique name part of the controller like main in Controller_main.php
     */
    function __construct ($rootUrl = "/", $fallBackCtrl = NULL) {
        // set urlOffset
        $rootUrl = count(explode("/", $rootUrl));
        $this->rootUrl = $rootUrl;

        // getPayload
        $url = $_SERVER['REQUEST_URI'];
        $this->packets = explode("/", $url);
        $this->filteredPackets = array_slice($this->packets, $this->rootUrl);

        // Set error messages
        $this->error = NULL;
        $this->errorMessage = NULL;

        // set fallBackCtrl
        $this->fallBackCtrl = $fallBackCtrl;

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

        // run fallback
        } else if ($this->fallBackCtrl) {
            $oldErrorMessage = $this->errorMessage;
            $oldErrorCode = $this->error;
            $this->ctrlName = $this->fallBackCtrl;
            $this->method = NULL;
            $this->params = NULL;

            if ($this->errorChecking()) {
                return $this->sendToDestination();

            } else {
                $this->errorMessage = "[firstError] => " . $oldErrorMessage . " [fallbackError] => " . $this->errorMessage;
                $this->error = $oldErrorCode;
                return FALSE;
            }
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
            if (!method_exists($this->controller, "mydefault") ) {
                $this->error = "E4";
                $this->errorMessage = "no method was given and no default found";
                return FALSE;
            }
            $this->method = "mydefault";
        }

        // checks if the method exists in the class
        // if not check if there is a default
        if (!method_exists($this->controller, $method) ) {
            if (!method_exists($this->controller, "mydefault") ) {
                $this->error = "E5";
                $this->errorMessage = "method doesn't exist in the controller_class and no default found";
                return FALSE;
            }
            $this->method = "mydefault";
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
