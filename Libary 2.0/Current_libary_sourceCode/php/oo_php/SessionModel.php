<?php

class SessionModel {
    public function __construct() {}
    public function __destruct() {}


    /***
    * @Description
    * Controls login System (needs to be revisited)*/
    public function AdminLogin() {
        $AdminSesionName = "admin";

        $message = NULL;
        $loggedIn = NULL;
        $admin_input = NULL;

        // check if Admin is allready logged in
        if (isset($_SESSION['user']) && $_SESSION['user'] == $AdminSesionName) {
            $loggedIn = 1;

        // Checks if login info is good
        } else {
            // boolean checks
            $UserConf = 0;
            $PassConf = 0;


            $adminHash = '$argon2i$v=19$m=1024,t=2,p=2$VkZFWnhaMEk2SDlRcmgyMg$A1OoUq05TSuSsaZDnohAlF+2ZG9A9dYVAhVHl+Lzjjw';
            $passHash = '$argon2i$v=19$m=1024,t=2,p=2$eEtsTU9RLlZDbVozMTFhbQ$PZ0fsLrf4m3w11yMyHRCKT8u859GwrdEJe9CuG9xPfc';

            $UserTry = "";
            $PassTry = "";

            $username = NULL;
            $password = NULL;

            if (isset($_POST['username'])) {
                $username = $_POST['username'];
                $UserTry = password_hash($username, 2);

                if (isset($_POST['password'])) {
                    $password = $_POST['password'];
                    $PassTry = password_hash($password, 2);
                }
            }

            // check for Username
            if ($username != NULL) {
                if (password_verify($username, $adminHash)) {
                    $UserConf = 1;
                } else {
                    $admin_input = $username;
                }
            }

            // check for password
            if ($password != NULL) {
                if (password_verify($password, $passHash)) {
                    $PassConf = 1;
                }
            }

            // check if all login information == correct
            if ($UserConf && $PassConf) {
                $loggedIn = 1;
                $_SESSION["user"] = $AdminSesionName;
            } else {
                if ($username != NULL || $password != NULL) {
                    $message = "gebruikersnaam of wachtwoord is foutief";
                }
            }
        }

        return [$loggedIn, $admin_input ,$message];
    }


    /***
    * @Description
    * Starts the session
    * Sets the session max duration
    * Sets the session timeout between Actions
    * Sets the expireTime of the sessionCookie
    * Sets the time before the garbae collection can collect the session
    * logs user out if the time has expired*/
    public function SessionSupport() {
        // set session vars
        $expireTime = 1800; // 30m
        $maxExpireTime = 10800; //3 hours
        $time = time();

        // start session
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
                $this->Logout();
            }
        } else {
            $_SESSION['timeout'] = $time;
        }
    }


    /***
    * @Description
    * Kills/destroys the session*/
    public function Logout() {
        session_unset();
        session_destroy();
        session_start();
    }

    /***
    * @Description
    * Adds 1 from the product amount in the cart*/
    public function AddToCart() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if (isset($_SESSION['products'][$id])) {
                $_SESSION['products']["$id"]++;
            } else {
                $_SESSION['products']["$id"] = 1;
            }
        }
    }

    /***
    * @Description
    * Removes 1 from the product amount in the cart*/
    public function RemoveFromCart() {
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

    /***
    * @Description
    * Generates a numberedArray From the SessionCart */
    public function WinkelwagenSession() {
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
