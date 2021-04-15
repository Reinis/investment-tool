<?php

namespace InvestmentTool\Services;

use InvalidArgumentException;
use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;
    private StockRepository $stockRepository;
    private FundsService $fundsService;

    public function __construct(TransactionRepository $transactionRepository, StockRepository $stockRepository, FundsService $fundsService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->stockRepository = $stockRepository;
        $this->fundsService = $fundsService;
    }

    public function add($symbol, $amount): void
    {
        $availableFunds = $this->fundsService->getAvailableFunds();
        $quote = $this->stockRepository->quote($symbol, true)->getC() * 1000;

        if ($quote * $amount > $availableFunds) {
            throw new InvalidArgumentException("Not enough funds");
        }

        $this->transactionRepository->add(new Transaction($symbol, $quote, $amount));
    }

    public function getAll(): Transactions
    {
        return $this->transactionRepository->getAll();
    }

    public function close(int $id): void
    {
        if ($this->transactionRepository->isClosed($id)) {
            throw new InvalidArgumentException("Transaction already closed");
        }

        $symbol = $this->getSymbol($id);
        $quote = $this->stockRepository->quote($symbol, true);
        $this->transactionRepository->close($id, $quote);
    }

    public function getSymbol(int $id): string
    {
        return $this->transactionRepository->getSymbol($id);
    }

    public function delete(int $id): void
    {
        $this->transactionRepository->delete($id);
    }
}
