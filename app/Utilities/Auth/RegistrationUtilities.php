<?php

namespace PurrPHP\App\Utilities\Auth;

use PurrPHP\App\Utilities\AbstractUtilities;
use PurrPHP\Http\JsonResponse;
use PurrPHP\Http\Response;
use Random\RandomException;


readonly class RegistrationUtilities extends AbstractUtilities {
    public static function rules(): array {
        return array(
            'login' => 'required|min:5|max:40',
            'password' => 'required|min:5',
        );
    }

    public static function successfulResponse($api_key): Response {
        return JsonResponse::create(array(
            'success' => true,
            'message' => __('Registration successful'),
            'api_key' => $api_key
        ), 201);
    }

    public static function loginAlreadyExistsResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('The login is already taken'),
            'errors' => array('login' => 'Login must be unique'),
            'error_code' => 'LOGIN_ALREADY_EXISTS',
        ), 409);
    }

    public static function registrationDisabledResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('New user registration is currently disabled'),
            'error_code' => 'REGISTRATION_DISABLED',
        ), 403);
    }

    public static function generateApiKey(): string {
        try {
            return bin2hex(random_bytes(32));
        } catch (RandomException) {
            return false;
        }
    }
}