<?php

namespace PurrPHP\App\Utilities;

use PurrPHP\Http\JsonResponse;
use PurrPHP\Http\Response;

readonly class AbstractUtilities {
    public static function validationErrorResponse($validation): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('Validation errors occurred'),
            'errors' => $validation->errors()->toArray(),
            'error_code' => 'VALIDATION_ERROR',
        ), 422);
    }

    public static function serverErrorResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('An unexpected error occurred. Please try again later'),
            'error_code' => 'INTERNAL_SERVER_ERROR',
        ), 500);
    }
}