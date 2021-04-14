<?php

namespace unit\Entities\Collections;

use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionsTest extends TestCase
{
    public function testEmptyTransactions(): Transactions
    {
        $transactions = new Transactions();

        self::assertCount(0, $transactions);

        return $transactions;
    }

    /**
     * @depends testEmptyTransactions
     * @param Transactions $transactions
     */
    public function testAdd(Transactions $transactions): void
    {
        self::assertCount(0, $transactions);

        $transaction = new Transaction('XYZ', 42, 5);
        $transactions->add($transaction);

        self::assertCount(1, $transactions);
    }
}
