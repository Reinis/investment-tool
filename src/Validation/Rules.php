<?php


namespace InvestmentTool\Validation;


use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;


class Rules implements IteratorAggregate, Countable
{
    /**
     * @var Rule[]
     */
    private array $rules = [];

    public function __construct(Rule ...$rules)
    {
        foreach ($rules as $rule) {
            $this->add($rule);
        }
    }

    public function add(Rule $rule): void
    {
        $name = $rule->getName();

        if (isset($this->rules[$name])) {
            throw new InvalidArgumentException("Condition '$name' already defined");
        }

        $this->rules[$name] = $rule;
    }

    public function check(string $name, string $value): bool
    {
        if (!isset($this->rules[$name])) {
            throw new InvalidArgumentException("Rule for '$name' not defined");
        }

        return $this->rules[$name]->check($value);
    }

    /**
     * @return ArrayIterator|Rule
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->rules);
    }

    public function count(): int
    {
        return count($this->rules);
    }
}
