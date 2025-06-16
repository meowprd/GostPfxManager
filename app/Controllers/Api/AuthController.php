<?php

namespace PurrPHP\App\Controllers\Api;

use PurrPHP\Controller\AbstractController;

use PurrPHP\App\Entities\User;
use PurrPHP\App\Services\UserService;

use PurrPHP\App\Utilities\Auth\RegistrationUtilities;
use PurrPHP\App\Utilities\Auth\LoginUtilities;

class AuthController extends AbstractController {

    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function register(): \PurrPHP\Http\Response {
        if(!REGISTRATION_ENABLED) { return RegistrationUtilities::registrationDisabledResponse(); }

        $validation = $this->validator->make(array(
            'login' => $this->request->input('login'),
            'password' => $this->request->input('password'),
        ), RegistrationUtilities::rules());
        $validation->validate();

        if($validation->fails()) { return RegistrationUtilities::validationErrorResponse($validation); }
        if($this->userService->doesLoginExist($this->request->input('login'))) { return RegistrationUtilities::loginAlreadyExistsResponse(); }

        $user = new User();
        $api_key = RegistrationUtilities::generateApiKey();
        if(!$api_key) { return RegistrationUtilities::serverErrorResponse(); }

        $user->setLogin($this->request->input('login'));
        $user->setPasswordWithHash($this->request->input('password'));
        $user->setIsBlocked(false);
        $user->setApiKey($api_key);
        $user = $this->userService->store($user);
        if(!$user) { return RegistrationUtilities::serverErrorResponse(); }

        return RegistrationUtilities::successfulResponse($api_key);
    }

    public function login() {
        $validation = $this->validator->make(array(
            'login' => $this->request->input('login'),
            'password' => $this->request->input('password'),
        ), LoginUtilities::rules());
        $validation->validate();

        if($validation->fails()) { return LoginUtilities::validationErrorResponse($validation); }
        $user = $this->userService->authorize($this->request->input('login'), $this->request->input('password'));
        if(!$user) { return LoginUtilities::incorrectDataResponse(); }
        if($user->isBlocked()) { return LoginUtilities::accountBlockedResponse(); }

        return LoginUtilities::successfulResponse($user->getApiKey());
    }
}