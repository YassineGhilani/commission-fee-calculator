<?php

namespace App;

use App\Currency\CurrencyConverterInterface;
use App\Fee\FeeCalculatorInterface;

class Calculator
{

    private CurrencyConverterInterface $currencyConverter;
    private FeeCalculatorInterface $feeCalculator;

    private array $userWithdrawals = [];

    public function __construct(CurrencyConverterInterface $currencyConverter, FeeCalculatorInterface $feeCalculator)
    {
        $this->currencyConverter = $currencyConverter;
        $this->feeCalculator = $feeCalculator;
    }

    public function calculate(array $transaction): string
    {
        $amount = $transaction['amount'];
        $currency = $transaction['currency'];
        $userId = $transaction['user_id'];
        $userType = $transaction['user_type'];
        $operationType = $transaction['operation_type'];
        $date = $transaction['operation_date'];

        // Convert the amount to EUR
        $amountInEur = $this->currencyConverter->convertToEur($amount, $currency);

        // Special case for private withdrawals
        if ($operationType === 'withdraw' && $userType === 'private') {
            $week = $this->getWeekFromDate($date);
            $this->initializeUserWeekData($userId, $week);

            // Calculate the remaining free amount for this week
            $freeAmountLeft = max(0, 1000 - $this->userWithdrawals[$userId][$week]['total']);
            $withdrawCount = $this->userWithdrawals[$userId][$week]['count'];

            // If under the 1000 EUR thresold or fewer than 3 withdrawals
            if ($withdrawCount < 3 && $freeAmountLeft > 0) {
                if ($amountInEur <= $freeAmountLeft) {
                    // No fee if the amount is within the free limit
                    $fee = 0;
                } else {
                    // Caculate the fee for the excess amount
                    $fee = $this->feeCalculator->calculateFee('withdraw', $userType, $amountInEur - $freeAmountLeft);
                }
            } else {
                // Standard fee if the threshold or free withdrawals are exceeded
                $fee = $this->feeCalculator->calculateFee('withdraw', $userType, $amountInEur);
            }
            // Update the totam and number of withdrawals for the user this week
            $this->userWithdrawals[$userId][$week]['total'] += $amountInEur;
            $this->userWithdrawals[$userId][$week]['count']++;
        } else {
            // Calculate the fee for the other types of operation
            $fee = $this->feeCalculator->calculateFee($operationType, $userType, $amountInEur);
        }

        // Convert the fee back to the original currency
        $feeInOriginalCurrency = $this->currencyConverter->convertFromEur($fee, $currency);

        // Round the fee amount
        return $this->roundUp($feeInOriginalCurrency, $currency);
    }

    private function getWeekFromDate(string $date): string
    {
        return date('o-W', strtotime($date));
    }

    private function initializeUserWeekData(int $userId, string $week)
    {
        if (!isset($this->userWithdrawals[$userId][$week])) {
            $this->userWithdrawals[$userId][$week] = ['total' => 0, 'count' => 0];
        }
    }
    private static function roundUp(float $amount, string $currency): string
    {
        $precision = $currency === 'JPY' ? 0 : 2; // JPY n'a pas de décimales
        return number_format(ceil($amount * pow(10, $precision)) / pow(10, $precision), $precision, '.', '');
    }
}