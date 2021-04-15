<?php

namespace InvestmentTool\Services;

use Finnhub\Model\Quote;
use InvestmentTool\Repositories\StockRepository;

class QuoteService
{
    private StockRepository $stockRepository;

    public function __construct(StockRepository $stockRepository)
    {
        $this->stockRepository = $stockRepository;
    }

    public function quote(string $symbol): Quote
    {
        return $this->stockRepository->quote($symbol);
    }
}
