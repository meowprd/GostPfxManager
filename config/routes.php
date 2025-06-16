<?php

use PurrPHP\Routing\Route;
use PurrPHP\Http\Response;

return array(
    // web
    // ...

    // api
    Route::post('/api/auth.register', array(\PurrPHP\App\Controllers\Api\AuthController::class, 'register')),
);