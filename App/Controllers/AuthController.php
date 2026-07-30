<?php

namespace App\Controllers;

use App\Helper\Session;
use App\Models\User;
use \Core\Controller;
use Core\View;

class AuthController extends Controller
{
    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
    }

    public function loginForm()
    {
        $session = Session::getInstance();
        $message = $session->message ?? '';

        if ($session->isSignedIn()) {
            header('Location: /realestate/public/listings');
            exit;
        }

        View::renderTemplate('Users/login.html', ['message' => $message]);
    }

    public function loginStore()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::where('email', $email)->first();
        $session = Session::getInstance();

        if ($user && password_verify($password, $user->password)) {
            $session->login($user);
            header('Location: /realestate/public/listings');
            exit;
        }

        $session->message("Your email or password is incorrect.");
        header('Location: /realestate/public/login');
        exit;
    }

    public function logout()
    {
        $session = Session::getInstance();
        if (!$session->isSignedIn()) {
            header('Location: /realestate/public/login');
            exit;
        }

        $session->logout();
        header('Location: /realestate/public/login');
        exit;
    }
}
