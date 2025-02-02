<?php

require_once __DIR__  . '/../../vendor/autoload.php';
require_once __DIR__ . '/ExchangeRateService.php';
require_once __DIR__ . '/BinCheckerService.php';
require_once __DIR__ . '/CommissionCalculate.php';


use App\Refactor\BinCheckerService;
use App\Refactor\CommissionCalculate;
use App\Refactor\ExchangeRateService;
use GuzzleHttp\Client;

if (!isset($argv[1]) || !file_exists($argv[1])) {
    die("File not specified or not found!\n");
}

$exchangeService = new ExchangeRateService("7VcJ3dntPvaPZ3frl4d6D38b9U4QLinS", new Client());
$binChecker = new BinCheckerService(new Client());
$commissionCalculator = new CommissionCalculate();

foreach (explode("\n", file_get_contents($argv[1])) as $row) {
    if (empty($row)) {
        continue;
    }

    $rowData = json_decode($row, true);
    if ($rowData === null) {
        echo "Decoding error for the line: $row\n";
        continue;
    }

    $bin = $rowData['bin'];
    $amount = $rowData['amount'];
    $currency = $rowData['currency'];
    $isEu = $binChecker->isEu($bin);
    $rate = $exchangeService->getExchangeRate($currency, 'EUR');
    $amntFixed = ($currency === 'EUR' || $rate) ? $amount : $amount / $rate;
    $commission = $commissionCalculator->calculate($amntFixed, $isEu);
    echo "$commission \n";
}
