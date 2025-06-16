<?php

use PurrPHP\Routing\Route;
use PurrPHP\Http\Response;

return array(
    // web
    // ...

    // api
    Route::post('/api/auth.register', array(\PurrPHP\App\Controllers\Api\AuthController::class, 'register')),
    Route::post('/api/auth.login', array(\PurrPHP\App\Controllers\Api\AuthController::class, 'login')),

);