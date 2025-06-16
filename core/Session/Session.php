<?php

namespace PurrPHP\Session;



class Session implements SessionInterface {

  private const FLASH_MESSAGES_KEY = 'flash_messages';
  
  public function start(): void { session_start(); }

  public function set(string $key, $value): void {
    $_SESSION[$key] = $value;
  }
  public function setFlash(string $type, string $message): void {
    $_SESSION[self::FLASH_MESSAGES_KEY][$type][] = $message;
  }
  public function get(string $key, $default = null) {
    return $_SESSION[$key] ?? $default;
  }
  public function getFlash(string $type): array {
    $flash = $_SESSION[self::FLASH_MESSAGES_KEY][$type] ?? array();
    unset($_SESSION[self::FLASH_MESSAGES_KEY][$type]);
    return $flash;
  }
  public function has(string $key): bool {
    return isset($_SESSION[$key]);
  }
  public function hasFlash(string $type): bool {
    return isset($_SESSION[self::FLASH_MESSAGES_KEY][$type]);
  }
  public function remove(string $key): void {
    unset($_SESSION[$key]);
  }
  public function removeFlash(): void {
    unset($_SESSION[self::FLASH_MESSAGES_KEY]);
  }

}