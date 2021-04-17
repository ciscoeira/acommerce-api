<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private $exchangerate;
    private $accessKey;

    public function __construct(HttpClientInterface $exchangerate, string $exchangeRateApiKey)
    {
        $this->exchangerate = $exchangerate;
        $this->accessKey = $exchangeRateApiKey;
    }

    public function exchangeRate(string $from, string $to): float
    {
        $params = [
            'access_key' => $this->accessKey,
            'base' => $from,
            'to' => $to
        ];

        $response = $this->exchangerate->request('GET', 'latest', ['query' => $params])->toArray();

        return $response['rates'][$to];
    }
}