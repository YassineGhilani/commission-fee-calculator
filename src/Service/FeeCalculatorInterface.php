<?php

namespace App\Fee;

interface FeeCalculatorInterface{
 public function calculateFee(string $operationType, string $userType, float $amount): float ;
}
