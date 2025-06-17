<?php

use PurrPHP\Routing\Route;
use PurrPHP\Http\Response;

return array(
    // web
    // ...
    Route::get('/', function() {
        phpinfo();
        return new Response();
    }),

    // api
    Route::post('/api/auth.register', array(\PurrPHP\App\Controllers\Api\CertController::class, 'register')),
    Route::post('/api/auth.login', array(\PurrPHP\App\Controllers\Api\CertController::class, 'login')),

    Route::post('/api/cert.upload', array(\PurrPHP\App\Controllers\Api\CertController::class, 'upload'), array(\PurrPHP\App\Middlewares\AuthenticateWithToken::class)),
    Route::get('/api/cert.download', array(\PurrPHP\App\Controllers\Api\CertController::class, 'download'), array(\PurrPHP\App\Middlewares\AuthenticateWithToken::class)),
    Route::get('/api/cert.downloadPublicPart', array(\PurrPHP\App\Controllers\Api\CertController::class, 'downloadPublicPart'), array(\PurrPHP\App\Middlewares\AuthenticateWithToken::class)),
    Route::get('/api/cert.list', array(\PurrPHP\App\Controllers\Api\CertController::class, 'getAllList'), array(\PurrPHP\App\Middlewares\AuthenticateWithToken::class)),
    Route::get('/api/cert.list/{id}', array(\PurrPHP\App\Controllers\Api\CertController::class, 'getById'), array(\PurrPHP\App\Middlewares\AuthenticateWithToken::class)),
);