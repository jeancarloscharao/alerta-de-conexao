<?php 

namespace App\Services;

use App\Gateways\HTTPClientGateway;
use GuzzleHttp\Exception\RequestException;

class SiteMonitor {
    private $httpGateway;
    private $downSites = [];

    public function __construct(HTTPClientGateway $httpGateway) {
        $this->httpGateway = $httpGateway;
    }

    public function checkSites(array $sites): array {
        foreach ($sites as $site) {
            try {
                $response = $this->httpGateway->request($site);
                if (!$this->isValidResponse($response)) {
                    $this->downSites[] = $site;
                }
            } catch (\Exception $e) {
                $this->downSites[] = $site;
            }
        }
        return $this->downSites;
    }

    private function isValidResponse($response): bool {
        return $response->getStatusCode() === 200;
    }
}
