<?php

namespace PurrPHP\Http;

readonly class JsonResponse {
    public static function create(
        array  $content = array(),
        ?int   $status  = 200,
        ?array $headers = array()
    ): Response
    {
        $headers['Content-Type'] = 'application/json';
        return new Response(
            json_encode($content),
            $status,
            $headers
        );
    }
}