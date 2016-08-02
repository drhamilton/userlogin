<?php
    class DB extends SQLite3
    {
        function __construct()
        {
            $this->open('db/database.sqlite');
        }

        function
    }
    $db = new DB();

    if (!$db){
        echo $db->lastErrorMsg();
    }
    else {
        echo "Opened DB";
    }



//    $db = new SQLite3('db/database.sqlite');
//    $db->exec('CREATE TABLE foo (bar STRING)');
//    $db->exec("INSERT INTO foo (bar) VALUES ('This is a test')");
//    $result = $db->query('SELECT bar FROM foo');
//    var_dump($result->fetchArray());

    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    switch  ($path) {
        case '/login':
            echo 'Logging you in!';
            break;
        case '/register':
            echo 'Hey, we are registering now!';
            break;
        default:
            echo 'Guess I will just render the homepage. You requested: ' . $path . '.' ;
            break;
    }

