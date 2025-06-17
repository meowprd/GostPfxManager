<?php

namespace PurrPHP\App\Utilities\Certificates;

use PurrPHP\App\Utilities\AbstractUtilities;
use PurrPHP\Http\JsonResponse;
use PurrPHP\Http\Response;
use Random\RandomException;


readonly class GetUtilities extends AbstractUtilities {
    public static function rules(): array {
        return array(
            'pfx_file' => 'required|uploaded_file|max:10M'
        );
    }
    public static function successfulResponse(array $list): Response {
        return JsonResponse::create(array(
            'success' => true,
            'list' => $list
        ), 200);
    }
}