<?php


namespace InvestmentTool\Services;


use InvestmentTool\Entities\Collections\Assets;
use InvestmentTool\Entities\Asset;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Repositories\TransactionRepository;


class AssetService
{
    private QuoteService $quoteService;
    private StockRepository $stockRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(
        QuoteService $quoteService,
        StockRepository $stockRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->quoteService = $quoteService;
        $this->stockRepository = $stockRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function get(): Assets
    {
        $assets = new Assets();
        $transactions = $this->transactionRepository->getActive();

        foreach ($transactions as $transaction) {
            $symbol = $transaction->getSymbol();
            $profile = $this->stockRepository->profile($symbol);
            $assets->add(
                new Asset(
                    $symbol,
                    $profile->getName(),
                    $profile->getLogo(),
                    $transaction->getQuote(),
                    $transaction->getAmount(),
                    $this->quoteService->quote($symbol)->getC() * 1000,
                    $transaction->getId(),
                )
            );
        }

        return $assets;
    }
}
