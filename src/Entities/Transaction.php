<?php

namespace InvestmentTool\Entities;

class Transaction
{
    private string $symbol;
    private int $quote;
    private int $amount;

    public function __construct(string $symbol, int $quote, int $amount)
    {
        $this->symbol = $symbol;
        $this->quote = $quote;
        $this->amount = $amount;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getQuote(): int
    {
        return $this->quote;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
