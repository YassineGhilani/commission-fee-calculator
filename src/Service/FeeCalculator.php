<?php

namespace App\Fee;

require_once __DIR__ . "/FeeCalculatorInterface.php";

class FeeCalculator implements FeeCalculatorInterface
{
    private $fees;

    public function __construct(array $fees)
    {
        $this->fees = $fees;
    }
    public function calculateFee(string $operationType, string $userType, float $amount): float
    {
        if (!isset($this->fees[$operationType][$userType])) {
            throw new \Exception("Fee configuration not found for $operationType and $userType");
        }

        $feeRate = $this->fees[$operationType][$userType];
        return $amount * $feeRate;
    }
}
