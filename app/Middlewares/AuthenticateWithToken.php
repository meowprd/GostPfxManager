<?php

namespace PurrPHP\App\Middlewares;

use DateTime;
use Doctrine\DBAL\Exception;
use PurrPHP\Middleware\MiddlewareInterface;
use PurrPHP\Middleware\RequestHandlerInterface;
use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use PurrPHP\Http\RedirectResponse;
use Doctrine\DBAL\Connection;

use PurrPHP\App\Utilities\Auth\LoginUtilities;
use PurrPHP\App\Entities\User;

class AuthenticateWithToken implements MiddlewareInterface {
    public function process(Request $request, Connection $database, RequestHandlerInterface $handler): Response {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if(!preg_match('/^Bearer\s(\S+)$/', $authHeader, $matches)) { return LoginUtilities::missingApiKey(); }
        $token = $matches[1];
        $user = $this->isTokenValid($database, $token);
        if(!$user) { return LoginUtilities::invalidApiKey(); }
        $request->session()->set('api_user', $user);

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