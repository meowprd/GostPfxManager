<?php

namespace PurrPHP\App\Utilities\Auth;

use PurrPHP\App\Utilities\AbstractUtilities;
use PurrPHP\Http\JsonResponse;
use PurrPHP\Http\Response;
use Random\RandomException;


readonly class LoginUtilities extends AbstractUtilities {
    public static function rules(): array {
        return array(
            'login' => 'required|min:5|max:40',
            'password' => 'required|min:5',
        );
    }

    public static function successfulResponse($api_key): Response {
        return JsonResponse::create(array(
            'success' => true,
            'message' => __('Authorization successful'),
            'api_key' => $api_key
        ), 200);
    }

    public static function incorrectDataResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('Incorrect login or password'),
            'errors' => array('login' => 'Incorrect', 'password' => 'Incorrect'),
            'error_code' => 'INCORRECT_LOGIN_OR_PASSWORD'
        ), 401);
    }

    public static function accountBlockedResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('Your account is temporarily blocked'),
            'error_code' => 'ACCOUNT_BLOCKED'
        ), 403);
    }
}