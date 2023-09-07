<?php 

namespace App\Gateways;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

interface HTTPClientGateway {
    public function request(string $url): Response;
}

class GuzzleHTTPClientGateway implements HTTPClientGateway {
    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function request(string $url): Response {
        // Exemplo de implementação usando Guzzle
        return $this->client->request('GET', $url);
    }
}
