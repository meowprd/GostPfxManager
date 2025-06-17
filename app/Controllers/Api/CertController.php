<?php

namespace PurrPHP\App\Controllers\Api;

use PurrPHP\Controller\AbstractController;

use PurrPHP\App\Services\CertificateService;
use PurrPHP\App\Utilities\Certificates\UploadUtilities;
class CertController extends AbstractController {
    public function __construct(
        private readonly CertificateService $certificateService,
    ) {}

    public function upload(): \PurrPHP\Http\Response {
        //dd($_FILES);
        $validation = $this->validator->make(array(
            'pfx_file' => $this->request->files()['pfx_file'],
        ), UploadUtilities::rules());
        $validation->validate();
        if($validation->fails()) { return UploadUtilities::validationErrorResponse($validation); }

        $uploadedFile = $this->request->files()['pfx_file'];
        $moveTo = UPLOADS_PATH . "/{$uploadedFile['name']}";
        $data = $this->certificateService->getCertificateInfo($uploadedFile['tmp_name'], '85245610');
        if(!$data) { return UploadUtilities::errorParseResponse(); }
        if($data->isExpired()) { return UploadUtilities::expiredErrorResponse(); }
        if(file_exists($moveTo)) { return UploadUtilities::fileAlreadyExistsErrorResponse(); }
        if(!move_uploaded_file($uploadedFile['tmp_name'], $moveTo)) { return UploadUtilities::serverErrorResponse(); }

        $cert = $this->certificateService->store($data);
        if(!$cert) { return UploadUtilities::serverErrorResponse(); }
        return UploadUtilities::successfulResponse();
    }
}