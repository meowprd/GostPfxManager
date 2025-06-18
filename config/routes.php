<?php

use PurrPHP\Routing\Route;

return array(
    // web
    // ...
    Route::get('/', array(\PurrPHP\App\Controllers\Web\IndexController::class, 'index')),
    Route::get('/login', array(\PurrPHP\App\Controllers\Web\AuthController::class, 'login'), array(\PurrPHP\App\Middlewares\Web\GuestAccess::class)),
    Route::get('/register', array(\PurrPHP\App\Controllers\Web\AuthController::class, 'register'), array(\PurrPHP\App\Middlewares\Web\GuestAccess::class)),
    Route::get('/dashboard', array(\PurrPHP\App\Controllers\Web\UserController::class, 'dashboard'), array(\PurrPHP\App\Middlewares\Web\UserAccess::class)),
    Route::get('/dashboard/certificate/{id}', array(\PurrPHP\App\Controllers\Web\UserController::class, 'certificate'), array(\PurrPHP\App\Middlewares\Web\UserAccess::class)),

    // api
    Route::post('/api/auth.register', array(\PurrPHP\App\Controllers\Api\AuthController::class, 'register')),
    Route::post('/api/auth.login', array(\PurrPHP\App\Controllers\Api\AuthController::class, 'login')),

    Route::post('/api/cert.upload', array(\PurrPHP\App\Controllers\Api\CertController::class, 'upload'), array(\PurrPHP\App\Middlewares\Api\AuthenticateWithToken::class)),
    Route::get('/api/cert.download', array(\PurrPHP\App\Controllers\Api\CertController::class, 'download'), array(\PurrPHP\App\Middlewares\Api\AuthenticateWithToken::class)),
    Route::get('/api/cert.downloadPublicPart', array(\PurrPHP\App\Controllers\Api\CertController::class, 'downloadPublicPart'), array(\PurrPHP\App\Middlewares\Api\AuthenticateWithToken::class)),
    Route::get('/api/cert.list', array(\PurrPHP\App\Controllers\Api\CertController::class, 'getAllList'), array(\PurrPHP\App\Middlewares\Api\AuthenticateWithToken::class)),
    Route::get('/api/cert.list/{id}', array(\PurrPHP\App\Controllers\Api\CertController::class, 'getById'), array(\PurrPHP\App\Middlewares\Api\AuthenticateWithToken::class)),
);