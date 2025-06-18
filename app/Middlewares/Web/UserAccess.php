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

class UserAccess implements MiddlewareInterface {
    public function process(Request $request, Connection $database, RequestHandlerInterface $handler): Response {
        if(isset($request->cookie()['access_token'])) {
            $user = $this->isTokenValid($database, $request->cookie()['access_token']);
            if(!$user) {
                unset($_COOKIE['access_token']);
                setcookie('access_token', '', -1, '/');
                $request->session()->remove('web_user');
                return new RedirectResponse('/login');
            } else {
                $request->session()->set('web_user', $user);
            }
        } else {
            $request->session()->remove('web_user');
            return new RedirectResponse('/login');
        }
        return $handler->handle($request);
    }

    private function isTokenValid(Connection $database, string $token): User|bool {
        $queryBuilder = $database->createQueryBuilder();
        try {
            $queryBuilder
                ->select('*')
                ->from('users')
                ->where('api_key = :token')
                ->setParameter('token', $token)
                ->setMaxResults(1)
                ->executeQuery();

            $data = $queryBuilder->fetchAssociative();
            if(!$data) { return false; }
            $user = new User();
            $user->setId($data['id']);
            $user->setLogin($data['login']);
            //$user->setPassword($data['password']);
            $user->setCreatedAt(new DateTime($data['created_at']));
            $user->setUpdatedAt(new DateTime($data['updated_at']));
            $user->setIsBlocked($data['is_blocked'] === 1);
            $user->setApiKey($data['api_key']);
            return $user;
        } catch (Exception $e) {
            return false;
        }
    }
}