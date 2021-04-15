<?php

namespace InvestmentTool\Repositories;

use Finnhub\Model\CompanyProfile2;
use Finnhub\Model\Quote;

interface StockRepository
{
    public function quote(string $symbol, bool $direct = false): Quote;

    public function profile(string $symbol): CompanyProfile2;
}
