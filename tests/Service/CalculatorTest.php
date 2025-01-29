<?php

require_once __DIR__ . "/../../src/Service/Calculator.php";
require_once __DIR__ . "/../../src/Service/CurrencyConverterInterface.php";
require_once __DIR__ . "/../../src/Service/FeeCalculatorInterface.php";
require_once __DIR__ . "/../../src/Service/CurrencyConverter.php";
require_once __DIR__ . "/../../src/Service/FeeCalculator.php";

use PHPUnit\Framework\TestCase;
use App\Calculator;
use App\Currency\CurrencyConverter;
use App\Fee\FeeCalculator;

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
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
        $exchangeRates = [
            "EUR"=> 1,
            "USD"=> 0.869768,
            "JPY"=> 0.00772,
        ];
    
        $currencyConverter = new CurrencyConverter($exchangeRates);
        $feeCalculator = new FeeCalculator($fees);

        $this->calculator = new Calculator($currencyConverter, $feeCalculator);
    }

    public function testDepositFee()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 1,
            'user_type' => 'private',
            'operation_type' => 'deposit',
            'amount' => 200.00,
            'currency' => 'EUR'
        ];

        $result = $this->calculator->calculate($transaction);
        $this->assertEquals('0.06', $result);
    }

    public function testPrivateWithdrawFeeBelowLimit()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 1,
            'user_type' => 'private',
            'operation_type' => 'withdraw',
            'amount' => 800.00,
            'currency' => 'EUR'
        ];

        $result = $this->calculator->calculate($transaction);
        $this->assertEquals('0.00', $result);
    }

    public function testPrivateWithdrawFeeAboveLimit()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 1,
            'user_type' => 'private',
            'operation_type' => 'withdraw',
            'amount' => 1200.00,
            'currency' => 'EUR'
        ];

        $result = $this->calculator->calculate($transaction);
        $this->assertEquals('0.60', $result);
    }

    public function testBusinessWithdrawFee()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 2,
            'user_type' => 'business',
            'operation_type' => 'withdraw',
            'amount' => 300.00,
            'currency' => 'EUR'
        ];

        $result = $this->calculator->calculate($transaction);
        $this->assertEquals('1.50', $result);
    }

    public function testCurrencyConversionJPY()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 1,
            'user_type' => 'private',
            'operation_type' => 'withdraw',
            'amount' => 30000,
            'currency' => 'JPY'
        ];

        $result = (float) $this->calculator->calculate($transaction);
        $this->assertEquals('0.00', $result);
    }

    public function testUnsupportedCurrency()
    {
        $transaction = [
            'operation_date' => '2023-01-01',
            'user_id' => 1,
            'user_type' => 'private',
            'operation_type' => 'withdraw',
            'amount' => 100.00,
            'currency' => 'ABC'
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Currency not supported: ABC");
        $this->calculator->calculate($transaction);
    }
}
