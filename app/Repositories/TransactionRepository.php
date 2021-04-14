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

    public function getSymbol(int $id): string;

    public function isClosed(int $id): bool;

    public function close(int $id, Quote $quote): void;

    public function getActive(): Transactions;

    public function getClosed(): Transactions;
}
