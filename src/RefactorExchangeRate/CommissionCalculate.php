<?php

namespace App\Refactor;

class CommissionCalculate{

    public function calculate(float $amount, bool $isEu):float 
    {
        $commissionRate = $isEu ? 0.01 : 0.02 ;
        return ceil($amount * $commissionRate * 100 ) / 100;
    }
}