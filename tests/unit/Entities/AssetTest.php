<?php

namespace unit\Entities;

use InvestmentTool\Entities\Asset;
use PHPUnit\Framework\TestCase;

class AssetTest extends TestCase
{
    public function testNewSymbol(): Asset
    {
        $asset = new Asset(
            'XYZ',
            'Big Corp',
            'link',
            123,
            3,
            125
        );

        self::assertEquals('XYZ', $asset->getSymbol());
        self::assertEquals('Big Corp', $asset->getName());
        self::assertEquals('link', $asset->getLogo());
        self::assertEquals(123, $asset->getPrice());
        self::assertEquals(3, $asset->getAmount());
        self::assertEquals(125, $asset->getValue());

        return $asset;
    }

    /**
     * @depends testNewSymbol
     * @param Asset $asset
     */
    public function testInvested(Asset $asset): void
    {
        self::assertEquals(369, $asset->invested());
    }

    /**
     * @depends testNewSymbol
     * @param Asset $asset
     */
    public function testCurrentValue(Asset $asset): void
    {
        self::assertEquals(375, $asset->currentValue());
    }

    /**
     * @depends testNewSymbol
     * @param Asset $asset
     */
    public function testDifference(Asset $asset): void
    {
        self::assertEquals(375/369*100-100, $asset->difference());
        self::assertEquals(1.626016260162615, $asset->difference());
    }
}
