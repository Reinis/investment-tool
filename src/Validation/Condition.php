<?php


namespace InvestmentTool\Validation;


use Closure;


class Condition
{
    private string $name;
    private Closure $callback;

    public function __construct(string $name, Closure $callback)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function check(string $value): bool
    {
        return ($this->callback)($value);
    }
}
