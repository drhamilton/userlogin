<?php

/**
 * Created by PhpStorm.
 * User: diz
 * Date: 7/30/16
 * Time: 10:42 PM
 */
class User
{
    public $email;
    public $name;
    public $username;
    public $password;

    public $UserModel;

    function __construct($db)
    {
        $this->UserModel = $db;
    }

    function login($user)
    {
        session_start();

        $response = array(
            'err' => null,
            'data' => null
        );

        foreach ($user as $key => $val){
            $user[$key] = $this->UserModel->escapeString($val);
        }

        extract($user);

        $stored_user = $this->getUser($username);

        if ($stored_user != false){
            if (password_verify($password, $stored_user['password']) == true){
                $return_user = array(
                    'name' => $stored_user['name'],
                    'username' => $stored_user['username'],
                    'email' => $stored_user['email']
                );

                $_SESSION['user'] = $return_user;
                $response['data'] = $return_user;
            }
            else {
                $response['err'] = 'Invalid Password';
            }
        }
        else {
            $response['err'] = 'Invalid Username';
        }

        return $response;
    }

    function getUser($username)
    {
        $response = $this->UserModel->query("SELECT * FROM users WHERE username='$username'");
        $user = $response->fetchArray(SQLITE3_ASSOC);

        if (!empty($user)){
            return $user;
        }
        else {
            return false;
        }
    }

    function create($user)
    {
        foreach ($user as $key => $val){
            $user[$key] = $this->UserModel->escapeString($val);
        }

        $response = array(
            'err' => null,
            'data' => null,
        );

        try {
            $userValid = $this->validate($user);
        } catch (Exception $e) {
            $response['err'] = $e->getMessage();
            return $response;
        }

        if ($userValid){
            extract($user);

            $hash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $this->UserModel->exec("INSERT INTO users (username, email, name, password) VALUES ('$username', '$email', '$name', '$hash')");
            } catch (Exception $e) {
                $response['err'] = $e->getMessage();
                return $response;
            }

            $response['data'] = $user;
        }

        return $response;
    }

    function validate($user){
        if (empty($user['username']) ||
            strlen($user['username']) > 20 ||
            strlen($user['username']) < 5 ||
            $this->usernameExists($user['username']))
        {
            throw new Exception('Username Invalid');
        }

        if (empty($user['email']) ||
            $this->emailExists($user['email']))
        {
            throw new Exception('Email Invalid');
        }

        if (empty($user['name'])){
            throw new Exception('Name Invalid');
        }

        if (empty($user['password']) ||
            strlen($user['password']) > 20 ||
            strlen($user['password']) < 5)
        {
            throw new Exception('Password Invalid');
        }


        return true;
    }

    function usernameExists($username)
    {
        $response = $this->UserModel->query("SELECT * FROM users WHERE username='$username'");

        if (!empty($response->fetchArray())){
            return true;
        }
        else {
            return false;
        }
    }

    function emailExists($email)
    {
        $response = $this->UserModel->query("SELECT * FROM users WHERE email='$email'");

        if (!empty($response->fetchArray())){
            return true;
        }
        else {
            return false;
        }
    }
}