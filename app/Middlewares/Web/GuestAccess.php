<?php

namespace PurrPHP\App\Middlewares\Web;

use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use PurrPHP\App\Entities\User;
use PurrPHP\App\Utilities\Auth\LoginUtilities;
use PurrPHP\Http\RedirectResponse;
use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use PurrPHP\Middleware\MiddlewareInterface;
use PurrPHP\Middleware\RequestHandlerInterface;

class GuestAccess implements MiddlewareInterface {
    public function process(Request $request, Connection $database, RequestHandlerInterface $handler): Response {
        if($request->session()->get('web_user') || isset($request->cookie()['access_token'])) {
            return new RedirectResponse('/dashboard');
        }
        return $handler->handle($request);
    }
}