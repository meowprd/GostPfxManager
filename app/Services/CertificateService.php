<?php

namespace PurrPHP\App\Services;

use DateTime;
use Doctrine\DBAL\Exception;
use PurrPHP\Entity\EntityService;

use PurrPHP\App\Entities\Certificate;

class CertificateService {

    public function __construct(private EntityService $entityService) {}

    public function store(Certificate $cert): Certificate|bool {
        $queryBuilder = $this->entityService->connection()->createQueryBuilder();
        try {
            $queryBuilder
                ->insert('certificates')
                ->values(array(
                    'subject' => ':subject',
                    'position' => ':position',
                    'snils' => ':snils',
                    'inn' => ':inn',
                    'mail' => ':mail',
                    'valid_from' => ':valid_from',
                    'valid_to' => ':valid_to',
                    'created_by' => ':created_by',
                    'stored_on' => ':stored_on',
                ))
                ->setParameters(array(
                    'subject' => $cert->getSubject(),
                    'position' => $cert->getPosition(),
                    'snils' => $cert->getSnils(),
                    'inn' => $cert->getInn(),
                    'mail' => $cert->getMail(),
                    'valid_from' => $cert->getValidFrom()->format('Y-m-d H:i:s'),
                    'valid_to' => $cert->getValidTo()->format('Y-m-d H:i:s'),
                    'created_by' => $cert->getCreatedBy(),
                    'stored_on' => $cert->getStoredOn(),
                ))
                ->executeQuery();
            $cert->setId($this->entityService->save($cert));
            return $cert;
        } catch (Exception $e) {
            return false;
        }
    }

    public function get(?int $id = null): array|false {
        $queryBuilder = $this->entityService->connection()->createQueryBuilder();
        try {
            $queryBuilder
                ->select('*')
                ->from('certificates');
            if($id) { $queryBuilder->where('id = :id')->setParameter('id', $id); }
            $queryBuilder
                ->orderBy('id', 'DESC')
                ->executeQuery();

            return $queryBuilder->fetchAllAssociative();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCertificateInfo(string $pfxPath, string $password): false|Certificate {
        $output = $this->getSystemOutput($pfxPath, $password);
        if(!$output) { return false; }

        $cert = $this->parseOutput($output);
        if(!$cert) { return false; }

        return $cert;
    }

    public function exportPublicKeyPart(string $pfxPath, string $password): false|array {
        $exportTo = APP_PATH . '/tmp/' . uniqid() . '.pem';
        $cmd = sprintf(
          'openssl pkcs12 -in %s -clcerts -nokeys -out %s -nodes -password pass:%s',
            escapeshellarg($pfxPath),
            escapeshellarg($exportTo),
            escapeshellarg($password)
        );

        exec($cmd, $output, $returnCode);
        if($returnCode !== 0) { return false; }
        return [true, $exportTo];
    }

    private function getSystemOutput($pfxPath, $password): false|array {
        $cmd = sprintf(
            '/opt/cprocsp/bin/amd64/certmgr -list -pfx -file %s -pin %s 2>&1',
            escapeshellarg($pfxPath),
            escapeshellarg($password)
        );
        exec($cmd, $output, $returnCode);
        if($returnCode !== 0) { return false; } // Ошибка команды
        return $output;
    }

    private function parseOutput($output): false|Certificate {
        $index = 2;

        $outputStr = implode("\n", $output);
        preg_match_all('/Subject\s+?:\s+(.+?)(?=\nIssuer|\nSerial|\nSHA1|\Z)/s', $outputStr, $subjects);
        preg_match_all('/Not valid before\s+?:\s+(.+?)\n/s', $outputStr, $validFrom);
        preg_match_all('/Not valid after\s+?:\s+(.+?)\n/s', $outputStr, $validTo);
        preg_match_all('/SHA1 Thumbprint\s+?:\s+(.+?)\n/s', $outputStr, $thumbprints);
        if(!isset($subjects[1][$index])) { return false; } // Не удалось найти информацию о субъекте

        $subject = $subjects[1][$index];
        $subjectData = array();
        preg_match_all('/([^=,]+)=("[^"]*"|[^,]*)/', $subject, $matches);
        foreach ($matches[1] as $i => $key) {
            $value = trim($matches[2][$i], ' "');
            $subjectData[trim($key)] = $value;
        }

        $subjectData['validFrom'] = $validFrom[1][$index];
        $subjectData['validTo'] = $validTo[1][$index];

        $cert = new Certificate();
        $cert->setSubject($subjectData['CN'] ?? '');
        $cert->setPosition($subjectData['T'] ?? '');
        $cert->setSnils($subjectData['СНИЛС'] ?? '');
        $cert->setInn($subjectData['ИНН'] ?? '');
        $cert->setMail($subjectData['E'] ?? '');
        $cert->setValidFrom(\DateTime::createFromFormat('d/m/Y H:i:s T', $subjectData['validFrom']));
        $cert->setValidTo(\DateTime::createFromFormat('d/m/Y H:i:s T', $subjectData['validTo']));

        return $cert;
    }
}