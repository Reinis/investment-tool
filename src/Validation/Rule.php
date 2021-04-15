<?php


namespace InvestmentTool\Validation;


class Rule
{
    private string $name;
    private Conditions $conditions;

    public function __construct(string $name, Conditions $conditions)
    {
        $this->name = $name;
        $this->conditions = $conditions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConditions(): Conditions
    {
        return $this->conditions;
    }

    public function check(string $value): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->check($value)) {
                return false;
            }
        }

        return true;
    }
}
