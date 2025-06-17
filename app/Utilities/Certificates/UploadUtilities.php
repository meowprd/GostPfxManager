<?php

namespace PurrPHP\App\Utilities\Certificates;

use PurrPHP\App\Utilities\AbstractUtilities;
use PurrPHP\Http\JsonResponse;
use PurrPHP\Http\Response;
use Random\RandomException;


readonly class UploadUtilities extends AbstractUtilities {
    public static function rules(): array {
        return array(
            'pfx_file' => 'required|uploaded_file|max:10M'
        );
    }
    public static function successfulResponse(): Response {
        return JsonResponse::create(array(
            'success' => true,
            'message' => __('Upload successful'),
        ), 200);
    }

    public static function errorParseResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('Error parsing uploaded file'),
            'error_code' => 'ERROR_PARSE_PFX'
        ), 400);
    }

    public static function expiredErrorResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('Certificate expired'),
            'error_code' => 'CERTIFICATE_EXPIRED'
        ), 422);
    }

    public static function fileAlreadyExistsErrorResponse(): Response {
        return JsonResponse::create(array(
            'success' => false,
            'message' => __('File already exists'),
            'error_code' => 'FILE_ALREADY_EXISTS'
        ), 409);
    }
}