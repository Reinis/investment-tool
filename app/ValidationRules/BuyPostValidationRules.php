<?php


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

        $exists = new Condition('exists', function (string $value) {
            return '' !== trim($value);
        });

        $rules->add(new Rule('symbol', new Conditions($exists)));
        $rules->add(new Rule('quote', new Conditions($exists)));
        $rules->add(new Rule('amount', new Conditions($exists)));

        return $rules;
    }
}
