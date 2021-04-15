<?php

declare(strict_types=1);


namespace InvestmentTool\ValidationRules;


use InvestmentTool\Validation\Condition;
use InvestmentTool\Validation\Conditions;
use InvestmentTool\Validation\Rule;
use InvestmentTool\Validation\Rules;


class BuyPostValidationRules
{
    public static function get(): Rules
    {
        $rules = new Rules();

        $exists = new Condition(
            'exists',
            function (string $value) {
                return '' !== trim($value);
            }
        );

        $number = new Condition(
            'number',
            function (string $value) {
                return false !== filter_var($value, FILTER_VALIDATE_FLOAT);
            }
        );

        $pozitive = new Condition(
            'pozitive',
            function (string $value) {
                return 0 < filter_var($value, FILTER_VALIDATE_FLOAT);
            }
        );

        $rules->add(new Rule('symbol', new Conditions($exists)));
        $rules->add(new Rule('amount', new Conditions($exists, $number, $pozitive)));

        return $rules;
    }
}
