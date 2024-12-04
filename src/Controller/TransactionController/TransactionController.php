<?php

namespace Controller\TransactionController;

use Controller\TransactionController\service\TransactionService;


class TransactionController
{
    public function __construct(private TransactionService $transaction_service) {}

    public function heal()
    {
        return $this->transaction_service->sanity();
    }

    public function get_transaction()
    {
        $amount_with_taxes = $this->transaction_service->convert_to_dolar_blue()->add_rate(21, 'IVA');

        return [
            'route' => '/transaction',
            'amount_without_taxes' => $this->transaction_service->get_amount(),
            'amount_with_taxes' => $amount_with_taxes,
            'amount_with_discount' => $this->transaction_service->apply_discount($amount_with_taxes['amount'], 10, '10% discount')
        ];
    }

    public function create()
    {
        echo "Transaction created";
    }
}
