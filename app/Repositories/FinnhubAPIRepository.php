<?php

namespace InvestmentTool\Repositories;

use Doctrine\Common\Cache\Cache;
use Finnhub\Api\DefaultApi;
use Finnhub\Model\Quote;

class FinnhubAPIRepository implements StockRepository
{
    private const CACHE_LIFETIME = 300;

    private DefaultApi $client;
    private Cache $cache;

    public function __construct(DefaultApi $client, Cache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    public function quote(string $symbol): Quote
    {
        $key = 'quote for ' . $symbol;

        if ($this->cache->contains($key)) {
            return $this->cache->fetch($key);
        }

        $quote = $this->client->quote($symbol);

        $this->cache->save($key, $quote, self::CACHE_LIFETIME);

        return $quote;
    }
}
