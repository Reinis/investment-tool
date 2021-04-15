<?php

declare(strict_types=1);


namespace InvestmentTool\Validation;


interface ValidatorInterface
{
    /**
     * @throws FailedValidationException
     */
    public function validate(string $name, array $values): void;
}
