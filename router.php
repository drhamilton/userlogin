<?php
/**
 * Created by PhpStorm.
 * User: diz
 * Date: 7/30/16
 * Time: 11:48 PM
 */
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    switch  ($path) {
        case '/login':
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                $user = $_POST;
                $response = $User->login($user);
                echo json_encode($response);
            }
            else {
                header('Location: /');
            }

            break;
        case '/logout':
            session_start();
            session_destroy();
            header('Location: /');

            break;
        case '/register':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $user = $_POST;
                $response = $User->create($user);
                echo json_encode($response);
            }
            else {
                header('Location: /');
            }

            break;
        case '/dashboard':
            session_start();

            if (isset($_SESSION['user'])){
                return require('dashboard.html');
            }
            else {
                header('Location: /');
            }

            break;
        default:
            return require('index.html');
            break;
    }