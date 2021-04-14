<?php

namespace InvestmentTool\Repositories;

use Finnhub\Api\DefaultApi;
use Finnhub\Model\Quote;

class FinnhubAPIRepository implements StockRepository
{
    private DefaultApi $client;

    public function __construct(DefaultApi $client)
    {
        $this->client = $client;
    }

    public function quote(string $symbol): Quote
    {
        return $this->client->quote($symbol);
    }
}
