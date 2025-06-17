<?php

namespace PurrPHP\App\Controllers\Api;

use PurrPHP\App\Utilities\AbstractUtilities;
use PurrPHP\Controller\AbstractController;

use PurrPHP\App\Services\DownloadService;
use PurrPHP\App\Services\CertificateService;
use PurrPHP\App\Utilities\Certificates\UploadUtilities;
use PurrPHP\App\Utilities\Certificates\GetUtilities;
use PurrPHP\Http\Response;

class CertController extends AbstractController {
    public function __construct(
        private readonly CertificateService $certificateService,
    ) {}

    public function upload(): \PurrPHP\Http\Response {
        $validation = $this->validator->make(array(
            'pfx_file' => $this->request->files()['pfx_file'],
        ), UploadUtilities::rules());
        $validation->validate();
        if($validation->fails()) { return UploadUtilities::validationErrorResponse($validation); }

        $uploadedFile = $this->request->files()['pfx_file'];
        $moveTo = UPLOADS_PATH . "/{$uploadedFile['name']}";
        $data = $this->certificateService->getCertificateInfo($uploadedFile['tmp_name'], DEFAULT_PFX_PASSWORD);
        if(!$data) { return UploadUtilities::errorParseResponse(); }
        if($data->isExpired()) { return UploadUtilities::expiredErrorResponse(); }
        if(file_exists($moveTo)) { return UploadUtilities::fileAlreadyExistsErrorResponse(); }
        if(!move_uploaded_file($uploadedFile['tmp_name'], $moveTo)) { return UploadUtilities::serverErrorResponse(); }

        $data->setCreatedBy($this->request->session()->get('api_user')->getLogin());
        $data->setStoredOn($moveTo);
        $cert = $this->certificateService->store($data);
        if(!$cert) { return UploadUtilities::serverErrorResponse(); }
        return UploadUtilities::successfulResponse();
    }

    /**
     * @throws \Exception
     */
    public function download(): Response {
        $validation = $this->validator->make(
            array('file' => $this->request->input('file')),
            array('file' => 'required')
        );
        $validation->validate();
        if($validation->fails()) { return AbstractUtilities::validationErrorResponse($validation); }
        $file = UPLOADS_PATH . '/' . $this->request->input("file");
        DownloadService::sendFileForDownload($file, $this->request->input("file"));
        return new Response();
    }

    /**
     * @throws \Exception
     */
    public function downloadPublicPart(): Response {
        $validation = $this->validator->make(
            array('file' => $this->request->input('file')),
            array('file' => 'required')
        );
        $validation->validate();
        if($validation->fails()) { return AbstractUtilities::validationErrorResponse($validation); }

        $file = UPLOADS_PATH . '/' . $this->request->input("file");
        [$status, $exportedPath] = $this->certificateService->exportPublicKeyPart($file, DEFAULT_PFX_PASSWORD);
        if(!$status) { return AbstractUtilities::serverErrorResponse(); }
        DownloadService::sendFileForDownload($exportedPath);
        return new Response();
    }

    public function getAllList() {
        $list = $this->certificateService->get();
        return GetUtilities::successfulResponse($list ?? array());
    }

    public function getById(int $id) {
        $cert = $this->certificateService->get($id);
        return GetUtilities::successfulResponse($cert ?? array());
    }
}