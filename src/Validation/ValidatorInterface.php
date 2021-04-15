<?php


namespace InvestmentTool\Validation;


interface ValidatorInterface
{
    public function validate(string $name, array $values): void;
}
