<?php

namespace InvestmentTool\Services;

use Finnhub\Model\Quote;
use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function add(Transaction $transaction): void
    {
        $this->transactionRepository->add($transaction);
    }

    public function getSymbol(int $id): string
    {
        return $this->transactionRepository->getSymbol($id);
    }

    public function getAll(): Transactions
    {
        return $this->transactionRepository->getAll();
    }

    public function close(int $id, Quote $quote): void
    {
        $this->transactionRepository->close($id, $quote);
    }

    public function delete(int $id): void
    {
        $this->transactionRepository->delete($id);
    }
}
