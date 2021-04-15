<?php

namespace InvestmentTool\Entities\Collections;

use ArrayIterator;
use Countable;
use InvestmentTool\Entities\Transaction;
use IteratorAggregate;

class Transactions implements IteratorAggregate, Countable
{
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    public function __construct(Transaction ...$transactions)
    {
        foreach ($transactions as $transaction) {
            $this->add($transaction);
        }
    }

    public function add(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @return ArrayIterator|Transaction[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->transactions);
    }

    public function count(): int
    {
        return count($this->transactions);
    }
}
