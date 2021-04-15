<?php

declare(strict_types=1);


namespace InvestmentTool\Entities;


use DateTime;


class Transaction
{
    private ?int $id;
    private string $symbol;
    private int $quote;
    private int $amount;
    private DateTime $quoteDate;
    private bool $closed;
    private ?int $closingValue;
    private ?DateTime $closingDate;

    public function __construct(
        string $symbol,
        int $quote,
        int $amount,
        bool $closed = false,
        ?int $id = null,
        ?int $closingValue = null,
        ?DateTime $closingDate = null,
        ?DateTime $quoteDate = null
    )
    {
        $this->symbol = $symbol;
        $this->quote = $quote;
        $this->amount = $amount;
        $this->id = $id;
        $this->closed = $closed;
        $this->closingValue = $closingValue;
        $this->closingDate = $closingDate;
        if ($quoteDate === null) {
            $this->quoteDate = new DateTime();
        } else {
            $this->quoteDate = $quoteDate;
        }
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function getClosingValue(): ?int
    {
        return $this->closingValue;
    }

    public function getClosingDate(): ?DateTime
    {
        return $this->closingDate;
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

    public function getId(): ?int
    {
        return $this->id;
    }
}
