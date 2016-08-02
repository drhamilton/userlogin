<?php

function initDB(){
    $db = new SQLite3(DB_NAME);
    $db->busyTimeout(5000);
    $db->exec('PRAGMA journal_mode = wal;');

    $db->exec("CREATE TABLE IF NOT EXISTS users (username TEXT PRIMARY KEY, email TEXT UNIQUE, name TEXT, password TEXT)");

    return $db;
}

define('DB_NAME', 'db/database.sqlite');
require './classes/User.php';

$db = initDB();
$User = new User($db);

