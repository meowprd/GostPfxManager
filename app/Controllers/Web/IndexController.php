<?php

namespace PurrPHP\App\Controllers\Web;

use PurrPHP\Controller\AbstractController;

class IndexController extends AbstractController {

    public function __construct() {}

    public function index() {
        return $this->render('index/index.html.twig', array(
            'title' => APP_NAME
        ));
    }
}