<?php

namespace PurrPHP\Template;
use PurrPHP\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory {
  public function __construct(
    private SessionInterface $session
  ) {}

  public function create(): Environment {
    $loader = new FilesystemLoader(VIEWS_PATH);
    $twig = new Environment($loader, array(
      'debug' => DEBUG,
      'cache' => false
    ));

    /* ----------------------------- Extensions ----------------------------- */
    $twig->addExtension(new DebugExtension());

    /* ------------------------------- Defines ------------------------------ */
    $twig->addGlobal('APP_NAME', APP_NAME);
    
    /* ------------------------------ Functions ----------------------------- */
    $twig->addFunction(new TwigFunction('session', array($this, 'getSession')));
    $twig->addFunction(new TwigFunction('basename', function ($path){
        return basename($path);
    }));

    return $twig;
  }

  private function getSession(): SessionInterface { return $this->session; }
}