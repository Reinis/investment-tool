<?php

namespace InvestmentTool\Controllers;

use Finnhub\ApiException;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Services\FundsService;
use InvestmentTool\Views\View;

class HomeController
{
    private TransactionRepository $transactionRepository;
    private StockRepository $stockRepository;
    private FundsService $fundsService;
    private View $view;

    public function __construct(TransactionRepository $transactionRepository, StockRepository $stockRepository, FundsService $fundsService, View $view)
    {
        $this->transactionRepository = $transactionRepository;
        $this->stockRepository = $stockRepository;
        $this->fundsService = $fundsService;
        $this->view = $view;
    }

    public function index(): string
    {
        $transactions = $this->transactionRepository->getAll();
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

        $this->transactionRepository->delete($id);

        header('Location: /');
    }

    public function close(array $vars): string
    {
        $id = $vars['id'] ?? 0;

        if ($id === 0) {
            $message = "Could no find the record";
            return $this->view->render('error', compact('message'));
        }

        $symbol = $this->transactionRepository->getSymbol($id);
        $quote = $this->stockRepository->quote($symbol);
        $this->transactionRepository->close($id, $quote);

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
        $transactions = $this->transactionRepository->getAll();
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

        $this->transactionRepository->add(new Transaction($symbol, $quote * 1000, $amount));

        header('Location: /');
    }
}
