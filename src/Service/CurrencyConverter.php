<?php

namespace App\Currency;

require_once __DIR__ ."/CurrencyConverterInterface.php";
class CurrencyConverter implements CurrencyConverterInterface{

    private $exchangeRates = [];

    public function __construct(array $exchangeRates = []){
        $this->exchangeRates = $exchangeRates;
    }

    public function convertToEur(float $amount, string $currency): float
    {
        if(!isset($this->exchangeRates[$currency])) {
            throw new \Exception("Currency not supported: $currency");
        }
        return round($amount * $this->exchangeRates[$currency],6);
    }

    public function convertFromEur($amount, string $currency): float
    {
        if(!isset($this->exchangeRates[$currency])) {
            throw new \Exception("Currency not supported: $currency");
        }
        return round($amount / $this->exchangeRates[$currency], 6);
    }


}