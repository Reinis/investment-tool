<?php

namespace Entities;

use InvestmentTool\Entities\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testGetSymbol(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);

        self::assertEquals('XYZ', $transaction->getSymbol());
    }

    public function testGetQuote(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);

        self::assertEquals(42, $transaction->getQuote());
    }

    public function testGetAmount(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);

        self::assertEquals(5, $transaction->getAmount());
    }
}
