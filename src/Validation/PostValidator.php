<?php

declare(strict_types=1);


namespace InvestmentTool\Validation;


class PostValidator implements ValidatorInterface
{
    /**
     * @var Rules[]
     */
    private array $rules = [];

    public function __construct(array $ruleSets)
    {
        foreach ($ruleSets as $name => $rules) {
            $this->rules[$name] = $rules;
        }
    }

    /**
     * @throws FailedValidationException
     */
    public function validate(string $name, array $values): void
    {
        $results = [];

        foreach ($this->rules[$name] as $rule) {
            $fieldName = $rule->getName();
            $results[$fieldName] = isset($values[$fieldName]) && $rule->check($values[$fieldName]);
        }

        if (!(bool)array_product($results)) {
            throw new FailedValidationException("Validation failed");
        }
    }
}
