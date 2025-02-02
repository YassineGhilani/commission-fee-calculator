<?php

namespace App\Refactor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExchangeRateService
{
    private string $apiKey;
    private Client $httpClient;

    public function __construct(string $apiKey, Client $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient ?? new Client();
    }

    public function getExchangeRate(string $base, string $symbol): float
    {
        $url = "https://api.apilayer.com/exchangerates_data/latest?symbols=$symbol&base=$base";
        $this->httpClient = new Client(['verify' => false]);
        try {
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'apikey' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data['rates'][$symbol] ?? 0;
        } catch (RequestException $e) {
            return 0;
        }
    }
}


