<?php
Define("BESTAND_DIEPTE", 7);

$url = $_SERVER['REQUEST_URI'];
$packets = explode("/", $url);
$path = array_slice($packets, 0, BESTAND_DIEPTE);
$path = implode($path, "/");
Define("APP_DIR", $path);

Define("DB_NAME", "Gameplayparty");
Define("DB_USERNAME", "root");
Define("DB_PASS", "");
Define("DB_SERVER_ADRESS", "localhost");
Define("DB_TYPE", "mysql");
?>
