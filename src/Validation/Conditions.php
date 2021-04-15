<?php


namespace InvestmentTool\Validation;


use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;


class Conditions implements IteratorAggregate, Countable
{
    /**
     * @var Condition[]
     */
    private array $conditions = [];

    public function __construct(Condition ...$conditions)
    {
        foreach ($conditions as $condition) {
            $this->add($condition);
        }
    }

    public function add(Condition $condition): void
    {
        $name = $condition->getName();

        if (isset($this->conditions[$name])) {
            throw new InvalidArgumentException("Condition '$name' already defined");
        }

        $this->conditions[$name] = $condition;
    }

    public function get(string $name): Condition
    {
        if (!isset($this->conditions[$name])) {
            throw new InvalidArgumentException("Condition '$name' not found");
        }

        return $this->conditions[$name];
    }

    /**
     * @return ArrayIterator|Condition
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->conditions);
    }

    public function count(): int
    {
        return count($this->conditions);
    }
}
