<?php

namespace PurrPHP\App\Controllers\Web;

use PurrPHP\Controller\AbstractController;

use PurrPHP\App\Services\CertificateService;

class UserController extends AbstractController {

    public function __construct(
        private CertificateService $certificateService,
    ) {}

    public function dashboard() {
        return $this->render('user/dashboard.html.twig', array(
            'title' => 'Кабинет',
            'active' => 'list',
            'user' => $this->request->session()->get('web_user'),
            'certificates' => $this->certificateService->get()
        ));
    }

    public function certificate(int $id) {
        $certificate = $this->certificateService->get($id)[0];
        $likeSubject = $this->certificateService->searchBySubject($certificate['subject']);

        return $this->render('user/showCertificate.html.twig', array(
            'title' => 'Просмотр сертификата',
            'user' => $this->request->session()->get('web_user'),
            'certificate' => $certificate,
            'likeSubject' => $likeSubject
        ));
    }
}