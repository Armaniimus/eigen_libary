<?php

class SessionModel {
    public function __construct() {}
    public function __destruct() {}

    /**
     * this method is used to create a passwordhash
     * @param  string $password  a password you want to hash
     * @return string            the hash that is returned
     */
    public function hashPassword($password = FALSE) {
        if ($password) {
            $passHash = password_hash($password, PASSWORD_DEFAULT);
            return $passHash;
        }

        return FALSE;
    }

    /***
    * @Description
    * Controls login System (needs to be revisited)*/

    /**
     * this method is used to check if the password can be converted into the supplied passwordHash
     * @param  string $userName this needs to be the users username
     * @param  string $password this needs to be the users filled in password
     * @param  string $passHash this needs to be a valid passwordhash
     * @return array            the loginbool and password error message are returned
     */
    public function login($userName = FALSE, $password = FALSE, $passHash = FALSE) {
        $message = NULL;
        $loggedIn = NULL;
        $passCheck = FALSE;

        // check if Admin is allready logged in
        if (isset($_SESSION["loginBool"]) && $_SESSION["loginBool"] === 1) {
            $loggedIn = 1;

        // Checks if login info is good
        } else {
            // check for password
            if ($password != NULL) {
                if (password_verify($password, $passHash)) {
                    $passCheck = 1;
                    $loggedIn = 2;
                }
            }

            // check if all login information == correct
            if (!$passCheck) {
                //login info incorrect
                $message = "E1";
            }
        }

        return [$loggedIn, $message];
    }


    /***
    * @Description
    * Starts the session
    * Sets the session max duration
    * Sets the session timeout between Actions
    * Sets the expireTime of the sessionCookie
    * Sets the time before the garbae collection can collect the session
    * logs user out if the time has expired*/

    /**
     * this method sets the following Headers
     *  session cookie itself
     *  cookie_httponly // to disable javascript interference with the session
     *  sets session.cookie_lifetme // to limit how long the session is valid
     *
     * this method then checks if the session is new
     *   if not is checks if its active for to long
     *      if so it resets the kills the session and creates a new one
     *   if it is then it sets the current time in the session
     */
    public function sessionSupport() {
        // set session vars
        $expireTime = 1800; // 30m
        $maxExpireTime = 10800; //3 hours
        $time = time();

        // start session
        ini_set('session.cookie_httponly', 1); // Sets header to disable javascript interaction with the sessioncookie
        ini_set('session.gc_maxlifetime', $expireTime); // sets time until marked as garbage on the server
        ini_set('session.cookie_lifetime', $maxExpireTime); // sets time until the cookie is thrown away in the browser
        session_start();

        // check if expiration time is smaller or equal then the difference between stored time vs current time
        // if yes store current time
        // if no destroy and restart session
        if (isset($_SESSION['timeout'])) {
            if ($time - $_SESSION['timeout'] <= $expireTime) {
                $_SESSION['timeout'] = $time;
            } else {
                $this->logout();
            }
        } else {
            $_SESSION['timeout'] = $time;
        }
    }


    /**
    * this method Kills/destroys the session*/
    public function logout() {
        session_unset();
        session_destroy();
        session_start();
    }

    /**
    * Adds 1 from the product amount in the cart*/
    public function addToCart() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if (isset($_SESSION['products'][$id])) {
                $_SESSION['products']["$id"]++;
            } else {
                $_SESSION['products']["$id"] = 1;
            }
        }
    }

    /**
    * Removes 1 from the product amount in the cart*/
    public function removeFromCart() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];


            if (isset($_SESSION['cart'][$id])) {

                if ($_SESSION['cart'][$id] <= 1) {
                    unset($_SESSION['cart']["$id"]);
                } else {
                    $_SESSION['cart']["$id"]--;
                }
            }
        }
    }

    /**
    * Generates a numberedArray From the SessionCart
    * @return array this method returns an array with all items in the cart
    */
    public function winkelwagenSession() {
        // if isset products
        if (isset($_SESSION['cart']) ) {
            $array = [];
            foreach($_SESSION['cart'] as $ProductID => $Amount) {
                array_push($array, ["id" => $ProductID, "aantal" => $Amount]);
            }

            return $array;
        }

        return 0;
    }
}
?>
