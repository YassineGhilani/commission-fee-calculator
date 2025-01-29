<?php

require_once __DIR__ . "/src/Service/CsvReader.php";
require_once __DIR__ . "/src/Service/Calculator.php";
require_once __DIR__ . "/src/Service/CurrencyConverter.php";
require_once __DIR__ . "/src/Service/FeeCalculator.php";

use App\Calculator;
use App\Currency\CurrencyConverter;
use App\Fee\FeeCalculator;
use App\CsvReader;


if ($argc < 2) {
    die("Usage! php script.php <input_file.csv>\n");
}

$inputFile = $argv[1];

try {
    $exchangeRates = [
        "EUR" => 1,
        "USD" => 0.869768,
        "JPY" => 0.00772,
    ];

    $fees = [
        'withdraw' => [
            'private' => 0.003, // 0.3%
            'business' => 0.005, // 0.5%
        ],
        'deposit' => [
            'private' => 0.0003, // 0.03%
            'business' => 0.0003,
        ],
    ];

    $currencyConverter = new CurrencyConverter($exchangeRates);
    $feeCalculator = new FeeCalculator($fees);

    $calculator = new Calculator($currencyConverter, $feeCalculator);


    $transactions = CsvReader::readCsv($inputFile);
    
    foreach ($transactions as $transaction) {
        $fee = $calculator->calculate($transaction);
        echo $fee . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "";
}
