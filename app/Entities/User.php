<?php

namespace PurrPHP\App\Entities;

use PurrPHP\Entity\AbstractEntity;

class User extends AbstractEntity {

    private int $id;
    private string $login;
    private string $password;
    private \DateTime|null $created_at;
    private \DateTime|null $updated_at;
    private bool $is_blocked;
    private string $api_key;

    public function __construct() {}

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPasswordWithHash(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime|null $updated_at
     */
    public function setUpdatedAt(?\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    /**
     * @param bool $is_blocked
     * @return void
     */
    public function setIsBlocked(bool $is_blocked): void
    {
        $this->is_blocked = $is_blocked;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * @param string $api_key
     * @return void
     */
    public function setApiKey(string $api_key): void
    {
        $this->api_key = $api_key;
    }
}