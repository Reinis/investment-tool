<?php


namespace InvestmentTool\Entities;


class Asset
{
    private ?int $id;
    private string $symbol;
    private string $name;
    private string $logo;
    private int $price;
    private int $amount;
    private int $value;

    public function __construct(
        string $symbol,
        string $name,
        string $logo,
        int $price,
        int $amount,
        int $value,
        ?int $id = null
    )
    {
        $this->id = $id;
        $this->symbol = $symbol;
        $this->name = $name;
        $this->logo = $logo;
        $this->price = $price;
        $this->amount = $amount;
        $this->value = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function difference(): float
    {
        return $this->currentValue() / $this->invested() * 100 - 100;
    }

    public function currentValue(): int
    {
        return $this->value * $this->amount;
    }

    public function invested(): int
    {
        return $this->price * $this->amount;
    }
}
