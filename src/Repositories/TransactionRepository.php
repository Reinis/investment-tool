<?php

namespace InvestmentTool\Repositories;

use Finnhub\Model\Quote;
use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;

interface TransactionRepository
{
    public function add(Transaction $transaction): void;

    public function searchBySymbol(string $symbol): Transactions;

    public function getAll(): Transactions;

    public function delete(int $id): void;

    public function getSymbol($id): string;

    public function close($id, Quote $quote): void;

    public function getInvestedAmount(): int;

    public function getEarnedAmount(): int;
}
