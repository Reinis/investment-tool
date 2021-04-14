<?php

namespace InvestmentTool\Repositories;

use Finnhub\Model\Quote;

interface StockRepository
{
    public function quote(string $symbol, bool $direct = false): Quote;
}
