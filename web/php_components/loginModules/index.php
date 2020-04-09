<?php
    require_once "DataHandler.php";
    require_once "Authorizator.php";

    DataHandler::setConn("localhost", "authorizator", "root", "");
    Authorizator::setupAuth("users", "permissions", 180);
    Authorizator::loginContinue();

    // $sql = "DROP TABLE IF EXISTS users;
    // DROP TABLE IF EXISTS permissions;";
    // DataHandler::noread($sql);
    //
    // Authorizator::createPermissionsTable();
    // Authorizator::createUsersTable();
    // Authorizator::addPermission("perm");
    // Authorizator::addPermission("perm2");
    //
    // Authorizator::registerUser("new", "12345", "myEmail@gmail.com");
    // Authorizator::login("new", "12345");
    // Authorizator::login("new", "123456");
    Authorizator::logout();
    Authorizator::changePassword("new", "12345", "123");
    Authorizator::login("new", "12345");
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    Authorizator::login("new", "123");
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";

    $perm1 = Authorizator::hasPermission("perm");
    $perm2 = Authorizator::hasPermission("perm2");
    $perm3 = Authorizator::hasPermission("perm3");

    echo "<pre>";
    var_dump($perm1);
    echo "</pre>";

    echo "<pre>";
    var_dump($perm2);
    echo "</pre>";

    echo "<pre>";
    var_dump($perm3);
    echo "</pre>";
 ?>
