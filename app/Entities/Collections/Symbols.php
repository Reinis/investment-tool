<?php


namespace InvestmentTool\Entities\Collections;


use ArrayIterator;
use Countable;
use InvestmentTool\Entities\Symbol;
use IteratorAggregate;


class Symbols implements IteratorAggregate, Countable
{
    /**
     * @var Symbol[]
     */
    private array $symbols = [];

    public function __construct(Symbol ...$symbols)
    {
        foreach ($symbols as $symbol) {
            $this->add($symbol);
        }
    }

    public function add(Symbol $symbol): void
    {
        $this->symbols[] = $symbol;
    }

    /**
     * @return ArrayIterator|Symbol[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->symbols);
    }

    public function count(): int
    {
        return count($this->symbols);
    }
}
