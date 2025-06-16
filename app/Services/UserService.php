<?php

namespace PurrPHP\App\Services;

use Doctrine\DBAL\Exception;
use PurrPHP\Entity\EntityService;
use PurrPHP\App\Entities\User;

class UserService {

    public function __construct(private EntityService $entityService) {}

    public function store(User $user): User|bool {
        $queryBuilder = $this->entityService->connection()->createQueryBuilder();
        try {
            $queryBuilder
                ->insert('users')
                ->values(array(
                    'login' => ':login',
                    'password' => ':password',
                    'is_blocked' => ':is_blocked',
                    'api_key' => ':api_key',
                ))
                ->setParameter('login', $user->getLogin())
                ->setParameter('password', $user->getPassword())
                ->setParameter('is_blocked', ($user->isBlocked() ? '1' : '0'))
                ->setParameter('api_key', $user->getApiKey())
                ->executeQuery();
            $user->setId($this->entityService->save($user));
            return $user;
        } catch (Exception $e) {
            return false;
        }
    }

    public function authorize(string $login, string $password): User|bool {
        $queryBuilder = $this->entityService->connection()->createQueryBuilder();
        try {
            $queryBuilder
                ->select('*')
                ->from('users')
                ->where('login = :login')
                ->setParameter('login', $login)
                ->setMaxResults(1)
                ->executeQuery();

            $data = $queryBuilder->fetchAssociative();
            if(!$data) { return false; }
            if(!password_verify($password, $data['password'])) { return false; }

            $user = new User();
            $user->setId($data['id']);
            $user->setLogin($data['login']);
            $user->setPassword($data['password']);
            $user->setCreatedAt(new \DateTime($data['created_at']));
            $user->setUpdatedAt(new \DateTime($data['updated_at']));
            $user->setIsBlocked($data['is_blocked'] === 1);
            $user->setApiKey($data['api_key']);
            return $user;
        } catch (Exception) {
            return false;
        }
    }

    public function doesLoginExist(string $login): bool {
        $queryBuilder = $this->entityService->connection()->createQueryBuilder();
        try {
            $queryBuilder
                ->select('COUNT(*)')
                ->from('users')
                ->where('login = :login')
                ->setParameter('login', $login)
                ->executeQuery();
            return $queryBuilder->fetchOne() ?? false;
        } catch (Exception) {
            return true;
        }
    }
}