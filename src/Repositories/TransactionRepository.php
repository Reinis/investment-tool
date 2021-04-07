<?php

namespace InvestmentTool\Repositories;

use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;

interface TransactionRepository
{
    public function add(Transaction $transaction): void;

    public function searchBySymbol(string $symbol): Transactions;

    public function getAll(): Transactions;
}
