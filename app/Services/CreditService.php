<?php

namespace App\Services;

class CreditService
{
    protected $options = [
        'Embafinance' => [
            3 => 9,
            6 => 11.5,
            12 => 17,
            18 => 22,
            24 => 30,
        ],
        'Prior' => [
            3 => 8,
            6 => 10.5,
            9 => 14,
            12 => 16,
            15 => 20,
            18 => 21,
            24 => 29,
        ],
    ];

    /**
     * Get available financing options based on the given price.
     *
     * @param float $price
     * @return array
     */
    public function getOptions(float $price): array
    {
        $financingOptions = [];

        foreach ($this->options as $organization => $terms) {
            foreach ($terms as $months => $rate) {
                $total = $price * (1 + $rate / 100); // Calculate total with interest
                $monthlyPayment = $total / $months; // Monthly installment
                $financingOptions[$organization][] = [
                    'months' => $months,
                    'rate' => $rate,
                    'total' => round($total, 2),
                    'monthly_payment' => round($monthlyPayment, 2),
                ];
            }
        }

        return $financingOptions;
    }
}
