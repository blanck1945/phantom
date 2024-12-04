<?php

namespace Controller\TransactionController\service;

use Controller\TransactionController\model\TransactionModel;

class TransactionService
{
    const DOLAR_BLUE = 1200;

    public function __construct(public TransactionModel $tx_model, private float $amount, private string $currency, private string $description)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->description = $description;
    }

    public function sanity() {
        return [
            'valor_dolar_blue' => self::DOLAR_BLUE
        ];
    }

    public function convert_to_dolar_blue(): TransactionService
    {
        $this->amount = round($this->amount / self::DOLAR_BLUE, 2);

        return $this;
    }

    public function add_rate(int $rate, string $description)
    {
        return [
            "amount" => $this->amount + $this->amount * $rate / 100,
            "description" => $description
        ];
    }

    public function apply_discount(float $total_amount, int $discount, string $description)
    {
        return [
            "amount" => round($total_amount - $total_amount * $discount / 100, 2),
            "description" => $description
        ];
    }

    public function get_amount()
    {
        return $this->amount;
    }
}
