<?php
    /**
     *
     */
    class Authorizator extends DataHandler {
        function __construct() {

        }
        private static $expireTime = 10800;
        private static $usersTable = NULL;
        private static $permissionsTable = NULL;

        private static $status = NULL;
        private static $permissions = NULL;
        private static $user_ID = NULL;
        private static $username = NULL;
        private static $role = NULL;

        public static function setupAuth($usersTable = "users", $permissionsTable = "permissions", $expireTime = 180) {
            self::$usersTable = $usersTable;
            self::$permissionsTable = $permissionsTable;
            self::$expireTime = $expireTime*60;
        }

        public static function createUsersTable() {
            if (self::$permissionsTable == NULL || self::$usersTable == NULL) {
                return NULL;
            }
            $usersTable = self::$usersTable;
            $permissionsTable = self::$permissionsTable;
            $sql = "CREATE TABLE IF NOT EXISTS `$usersTable`(
                `user_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `permissions_ID` INT UNSIGNED NULL DEFAULT 1,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`user_ID`),
                FOREIGN KEY (`permissions_ID`) REFERENCES `$permissionsTable`(`permissions_ID`)
                ON UPDATE CASCADE ON DELETE RESTRICT
            );";

            self::noread($sql);
        }

        public static function createPermissionsTable() {
            if (self::$permissionsTable == NULL) {
                return NULL;
            }

            $permissionsTable = self::$permissionsTable;
            $sql = "CREATE TABLE IF NOT EXISTS `$permissionsTable`(
                `permissions_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `role` VARCHAR(50) NULL,
                PRIMARY KEY (`permissions_ID`)
            ); INSERT INTO `$permissionsTable` (role)
            VALUES ('DEFAULT')";

            self::noread($sql);
        }

        public static function addPermission($permission) {
            if (self::$permissionsTable == NULL) {
                return NULL;
            }

            $permissionsTable = self::$permissionsTable;
            $sql = "ALTER TABLE $permissionsTable
            ADD $permission TINYINT NOT NULL DEFAULT 0";

            self::noread($sql);
        }

        public static function registerUser($username, $pass, $email) {
            if (self::$usersTable == NULL) {
                return NULL;
            }

            $pass = password_hash( trim($pass), PASSWORD_DEFAULT );
            $email = trim($email);
            $username = trim($username);

            if ( filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($username) <= 50) {
                $usersTable = self::$usersTable;
                $sql = "INSERT INTO `users` (username, password, email)
                VALUES (:username, :pass, :email)";

                self::noread($sql, ["username" => $username, "pass" => $pass, "email" => $email]);
            }
        }

        public static function changePassword($username, $pass, $newPass) {
            if (self::$usersTable == NULL) {
                return NULL;
            }
            $usersTable = self::$usersTable;

            $sql = "SELECT user_ID, permissions_ID, username, password
            FROM `$usersTable`
            WHERE username = :username";
            $user = self::read($sql, ["username" => $username], 0);

            if (password_verify($pass, $user["password"]) ) {
                $newPass = password_hash( trim($newPass), PASSWORD_DEFAULT );

                $sql = "UPDATE $usersTable
                SET password=:pass
                WHERE username = :username";

                self::noread($sql, ["pass" => $newPass, "username" => $username]);
            }
        }

        public static function login($username, $password) {
            if (self::$usersTable == NULL || self::$permissionsTable == NULL) {
                return NULL;
            }

            $auth = [];
            $usersTable = self::$usersTable;
            $permissionsTable = self::$permissionsTable;

            $sql = "SELECT user_ID, permissions_ID, username, password
            FROM `$usersTable`
            WHERE username = :username";
            $user = self::read($sql, ["username" => $username], 0);

            if (password_verify($password, $user["password"]) ) {
                $auth["status"] = 1;
                $auth["loginTime"] = time();
                $auth["user_ID"] = $user["user_ID"];
                $auth["username"] = $user["username"];

                $sql = "SELECT * FROM `$permissionsTable` WHERE permissions_ID = :ID";
                $permissions = self::read($sql, ["ID" => $user["permissions_ID"], ], 0);
                $auth["role"] = $permissions["role"];
                unset( $permissions["role"] );
                unset( $permissions["permissions_ID"] );
                unset( $permissions["created_at"] );
                unset( $permissions["updated_at"] );
                $auth["permissions"] = $permissions;

                //set authorization data to model and session
                self::$status       = $auth["status"];
                self::$role         = $auth["role"];
                self::$user_ID      = $auth["user_ID"];
                self::$username     = $auth["username"];
                self::$permissions  = $auth["permissions"];
                $_SESSION["authorizator"] = $auth;
            };

        }

        public static function loginContinue() {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if ( isset($_SESSION["authorizator"]) ) {
                $auth = $_SESSION["authorizator"];

                if ( isset($auth["status"]) && $auth["status"] == 0 ) {
                    self::logout();

                } elseif ( isset($auth["loginTime"]) && (time() - $auth["loginTime"]) > self::$expireTime ) {
                    self::logout();

                } else {
                    self::$status       = $auth["status"];
                    self::$role         = $auth["role"];
                    self::$user_ID      = $auth["user_ID"];
                    self::$username     = $auth["username"];
                    self::$permissions  = $auth["permissions"];
                }
            }
        }

        public static function logout() {
            unset( $_SESSION["authorizator"] );
            self::$status = NULL;
            self::$role = NULL;
            self::$user_ID = NULL;
            self::$username = NULL;
            self::$permissions = NULL;
        }

        public static function hasPermission($name) {
            $permissions = self::$permissions;
            if ( isset($permissions[$name]) ) {
                return $permissions[$name];
            }
            return NULL;
        }

        public static function is_loggedIn() {
            if (self::$status) {
                return TRUE;
            }
            return FALSE;
        }

        public static function GET_user_ID() {
            return self::$user_ID;
        }

        public static function GET_username() {
            return self::$username;
        }

        public static function GET_role() {
            return self::$role;
        }
    }

 ?>
