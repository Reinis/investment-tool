<?php

namespace unit\Entities;

use InvestmentTool\Entities\Symbol;
use PHPUnit\Framework\TestCase;

class SymbolTest extends TestCase
{
    public function testNewSymbol(): Symbol
    {
        $symbol = new Symbol(
            'XYZ',
            'Big Corp',
            'link',
            123,
            3,
            125
        );

        self::assertEquals('XYZ', $symbol->getSymbol());
        self::assertEquals('Big Corp', $symbol->getName());
        self::assertEquals('link', $symbol->getLogo());
        self::assertEquals(123, $symbol->getPrice());
        self::assertEquals(3, $symbol->getAmount());
        self::assertEquals(125, $symbol->getValue());

        return $symbol;
    }

    /**
     * @depends testNewSymbol
     * @param Symbol $symbol
     */
    public function testInvested(Symbol $symbol): void
    {
        self::assertEquals(369, $symbol->invested());
    }

    /**
     * @depends testNewSymbol
     * @param Symbol $symbol
     */
    public function testCurrentValue(Symbol $symbol): void
    {
        self::assertEquals(375, $symbol->currentValue());
    }

    /**
     * @depends testNewSymbol
     * @param Symbol $symbol
     */
    public function testDifference(Symbol $symbol): void
    {
        self::assertEquals(375/369*100-100, $symbol->difference());
        self::assertEquals(1.626016260162615, $symbol->difference());
    }
}
