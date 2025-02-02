<?php

namespace App\Refactor;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BinCheckerService
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient ?? new Client();
    }

    public function isEu(string $bin): bool
    {
        $url = "https://data.handyapi.com/bin/" . $bin;
        try {
            $response = $this->httpClient->get($url);
            $data = json_decode($response->getBody()->getContents());

            return in_array($data->Country->A2, $this->getEuCountries());
        } catch (RequestException $e) {
            return false;
        }
    }

    private function getEuCountries(): array
    {
        return [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR',
            'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL',
            'PT', 'RO', 'SE', 'SI', 'SK'
        ];
    }
}