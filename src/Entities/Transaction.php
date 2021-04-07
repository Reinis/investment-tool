<?php

namespace InvestmentTool\Entities;

use DateTime;

class Transaction
{
    private string $symbol;
    private int $quote;
    private int $amount;
    private DateTime $quoteDate;

    public function __construct(string $symbol, int $quote, int $amount)
    {
        $this->symbol = $symbol;
        $this->quote = $quote;
        $this->amount = $amount;
        $this->quoteDate = new DateTime();
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

    public function getQuoteDate(): DateTime
    {
        return $this->quoteDate;
    }
}
