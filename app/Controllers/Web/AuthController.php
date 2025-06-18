<?php

namespace PurrPHP\App\Controllers\Web;

use PurrPHP\Controller\AbstractController;

class AuthController extends AbstractController {

    public function __construct() {}

    public function login() {
        return $this->render('auth/login.html.twig', array(
            'title' => 'Авторизация'
        ));
    }

    public function register() {
        return $this->render('auth/register.html.twig', array(
            'title' => 'Регистрация'
        ));
    }
}