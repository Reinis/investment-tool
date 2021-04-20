<?php


namespace InvestmentTool\Entities\Collections;


use ArrayIterator;
use Countable;
use InvestmentTool\Entities\Asset;
use IteratorAggregate;


class Assets implements IteratorAggregate, Countable
{
    /**
     * @var Asset[]
     */
    private array $assets = [];

    public function __construct(Asset ...$assets)
    {
        foreach ($assets as $asset) {
            $this->add($asset);
        }
    }

    public function add(Asset $asset): void
    {
        $this->assets[] = $asset;
    }

    public function getTotalDifference(): int
    {
        return array_sum(array_map(static fn(Asset $asset): int => $asset->currentValue() - $asset->invested(), $this->assets));
    }

    /**
     * @return ArrayIterator|Asset[]
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->assets);
    }

    public function count(): int
    {
        return count($this->assets);
    }
}
