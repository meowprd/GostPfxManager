<?php

namespace PurrPHP\App\Services;

class DownloadService {

    public function __construct() {}

    public static function sendFileForDownload(string $filePath, string $customName = ''): void {
        if(!file_exists($filePath)) { throw new \Exception(__("File does not exist")); }
        if(is_dir($filePath)) { throw new \Exception(__("This is directory")); }

        $downloadName = $customName ?: basename($filePath);
        $safeName = preg_replace('/[^\w\.\-]/u', '_', $downloadName);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        if(ob_get_level()) { ob_end_clean(); }

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $safeName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        readfile($filePath);
        exit;
    }
}