<?php

namespace PurrPHP\App\Entities;

use PurrPHP\Entity\AbstractEntity;

class Certificate extends AbstractEntity {

    private int $id;
    private string $subject;
    private string $position;
    private string $snils;
    private string $inn;
    private string $mail;
    private \DateTime $validFrom;
    private \DateTime $validTo;
    private string $createdBy;
    private string $storedOn;

    public function __construct() {}

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    public function getSnils(): string
    {
        return $this->snils;
    }

    public function setSnils(string $snils): void
    {
        $this->snils = $snils;
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function setInn(string $inn): void
    {
        $this->inn = $inn;
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTime $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getValidTo(): \DateTime
    {
        return $this->validTo;
    }

    public function setValidTo(\DateTime $validTo): void
    {
        $this->validTo = $validTo;
    }

    public function isExpired(): bool {
        $validTo = $this->validTo->setTimezone(new \DateTimeZone('UTC'));
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        return $now > $validTo;
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getStoredOn(): string
    {
        return $this->storedOn;
    }

    public function setStoredOn(string $storedOn): void
    {
        $this->storedOn = $storedOn;
    }

}