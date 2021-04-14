<?php

namespace InvestmentTool\Services;

use InvestmentTool\Repositories\TransactionRepository;

class FundsService
{
    private const BUDGET = 1_000_000;

    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getAvailableFunds(): int
    {
        return self::BUDGET
            - $this->getInvestedAmount()
            + $this->getEarnedAmount();
    }

    public function getInvestedAmount(): int
    {
        $total = 0;

        foreach ($this->transactionRepository->getActive() as $transaction) {
            $total += $transaction->getQuote() * $transaction->getAmount();
        }

        return $total;
    }

    public function getEarnedAmount(): int
    {
        $total = 0;

        foreach ($this->transactionRepository->getClosed() as $transaction) {
            $total += ($transaction->getClosingValue() - $transaction->getQuote()) * $transaction->getAmount();
        }

        return $total;
    }
}
