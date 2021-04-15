<?php


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

    public function validate(string $name, array $values): void
    {
        $results = [];

        foreach ($values as $key => $value) {
            $results[$key] = $this->rules[$name]->check($key, $value);
        }

        if (!(bool)array_product($results)) {
            throw new FailedValidationException("Validation failed");
        }
    }
}
