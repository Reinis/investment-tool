<?php


namespace InvestmentTool\ValidationRules;


use InvestmentTool\Validation\Condition;
use InvestmentTool\Validation\Conditions;
use InvestmentTool\Validation\Rule;
use InvestmentTool\Validation\Rules;


class QuotePostValidationRules
{
    public static function get(): Rules
    {
        $rules = new Rules();

        $exists = new Condition('exists', function (string $value) {
            return '' !== trim($value);
        });

        $quote = new Condition('quote', function (string $value) {
            return $value === 'quote';
        });

        $rules->add(new Rule('method', new Conditions($exists, $quote)));
        $rules->add(new Rule('symbol', new Conditions($exists)));
        $rules->add(new Rule('quote', new Conditions($exists)));

        return $rules;
    }
}
