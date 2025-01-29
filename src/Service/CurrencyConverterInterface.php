<?php

namespace App\Currency;

interface CurrencyConverterInterface{
    public function convertToEur(float $amount, string $currency): float;
    public function convertFromEur($amount, string $currency): float;
}