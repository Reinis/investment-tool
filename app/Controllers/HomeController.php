<?php

namespace InvestmentTool\Controllers;

use Finnhub\ApiException;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Services\FundsService;
use InvestmentTool\Services\TransactionService;
use InvestmentTool\Views\View;

class HomeController
{
    private StockRepository $stockRepository;
    private TransactionService $transactionService;
    private FundsService $fundsService;
    private View $view;

    public function __construct(StockRepository $stockRepository, TransactionService $transactionService, FundsService $fundsService, View $view)
    {
        $this->stockRepository = $stockRepository;
        $this->transactionService = $transactionService;
        $this->fundsService = $fundsService;
        $this->view = $view;
    }

    public function index(): string
    {
        $transactions = $this->transactionService->getAll();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('home', compact('transactions', 'availableFunds'));
    }

    public function delete(array $vars): string
    {
        $id = $vars['id'] ?? 0;

        if ($id === 0) {
            $message = "Could no find the record";
            return $this->view->render('error', compact('message'));
        }

        $this->transactionService->delete($id);

        header('Location: /');
    }

    public function close(array $vars): string
    {
        $id = $vars['id'] ?? 0;

        if ($id === 0) {
            $message = "Could no find the record";
            return $this->view->render('error', compact('message'));
        }

        $symbol = $this->transactionService->getSymbol($id);
        $quote = $this->stockRepository->quote($symbol);
        $this->transactionService->close($id, $quote);

        header('Location: /');
    }

    public function getQuote(): string
    {
        $method = $_POST['method'] ?? 'none';
        $symbol = trim($_POST['symbol']) ?? 'none';

        if ($method !== 'quote' || $symbol === 'none') {
            $message = "Invalid query";
            return $this->view->render('error', compact('message'));
        }

        $quote = $this->stockRepository->quote($symbol);
        $transactions = $this->transactionService->getAll();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('home', compact('symbol', 'quote', 'transactions', 'availableFunds'));
    }

    public function quote(array $vars): string
    {
        try {
            $quote = $this->stockRepository->quote($vars['symbol']);
        } catch (ApiException $e) {
            $message = "API request failed";
            return $this->view->render('error', compact('message'));
        }

        return $quote->getModelName() . ' ' . $quote->getC() . PHP_EOL;
    }

    public function buy(): string
    {
        $symbol = $_POST['symbol'] ?? 'none';
        $quote = $_POST['quote'] ?? 'none';
        $amount = $_POST['amount'] ?? 'none';

        if ($symbol === 'none' || $quote === 'none' || $amount === 'none') {
            $message = "Invalid input";
            return $this->view->render('error', compact('message'));
        }

        $availableFunds = $this->fundsService->getAvailableFunds();

        if ($quote * 1000 * $amount > $availableFunds) {
            $message = "Not enough funds";
            return $this->view->render('error', compact('message'));
        }

        $this->transactionService->add(new Transaction($symbol, $quote * 1000, $amount));

        header('Location: /');
    }
}
