<?php

namespace PurrPHP\Http;

use PurrPHP\Http\Response;

class RedirectResponse extends Response {

  public function __construct(string $url) {
    parent::__construct('', 302, array('Location' => $url));
  }
}