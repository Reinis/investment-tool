<?php

namespace InvestmentTool\Controllers;

use Finnhub\ApiException;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Views\View;

class HomeController
{
    private const BUDGET = 1_000_000;

    private TransactionRepository $transactionRepository;
    private StockRepository $stockRepository;
    private View $view;

    public function __construct(TransactionRepository $transactionRepository, StockRepository $stockRepository, View $view)
    {
        $this->transactionRepository = $transactionRepository;
        $this->stockRepository = $stockRepository;
        $this->view = $view;
    }

    public function index(): void
    {
        $transactions = $this->transactionRepository->getAll();
        $availableFunds = $this->getAvailableFunds();

        echo $this->view->render('home', compact('transactions', 'availableFunds'));
    }

    private function getAvailableFunds(): int
    {
        return self::BUDGET
            - $this->transactionRepository->getInvestedAmount()
            + $this->transactionRepository->getEarnedAmount();
    }

    public function delete(array $vars): void
    {
        $id = $vars['id'] ?? 0;

        if ($id === 0) {
            $message = "Could no find the record";
            echo $this->view->render('error', compact('message'));
            die();
        }

        $this->transactionRepository->delete($id);

        header('Location: /');
    }

    public function close(array $vars): void
    {
        $id = $vars['id'] ?? 0;

        if ($id === 0) {
            $message = "Could no find the record";
            echo $this->view->render('error', compact('message'));
            die();
        }

        $symbol = $this->transactionRepository->getSymbol($id);
        $quote = $this->stockRepository->quote($symbol);
        $this->transactionRepository->close($id, $quote);

        header('Location: /');
    }

    public function getQuote(): void
    {
        $method = $_POST['method'] ?? 'none';
        $symbol = trim($_POST['symbol']) ?? 'none';

        if ($method !== 'quote' || $symbol === 'none') {
            $message = "Invalid query";
            echo $this->view->render('error', compact('message'));
            die();
        }

        $quote = $this->stockRepository->quote($symbol);
        $transactions = $this->transactionRepository->getAll();
        $availableFunds = $this->getAvailableFunds();

        echo $this->view->render('home', compact('symbol', 'quote', 'transactions', 'availableFunds'));
    }

    public function quote(array $vars): void
    {
        try {
            $quote = $this->stockRepository->quote($vars['symbol']);
        } catch (ApiException $e) {
            $message = "API request failed";
            echo $this->view->render('error', compact('message'));
            die();
        }

        echo $quote->getModelName() . ' ' . $quote->getC() . PHP_EOL;
    }

    public function buy(): void
    {
        $symbol = $_POST['symbol'] ?? 'none';
        $quote = $_POST['quote'] ?? 'none';
        $amount = $_POST['amount'] ?? 'none';

        if ($symbol === 'none' || $quote === 'none' || $amount === 'none') {
            $message = "Invalid input";
            echo $this->view->render('error', compact('message'));
            die();
        }

        $availableFunds = $this->getAvailableFunds();

        if ($quote * 1000 * $amount > $availableFunds) {
            $message = "Not enough funds";
            echo $this->view->render('error', compact('message'));
            die();
        }

        $this->transactionRepository->add(new Transaction($symbol, $quote * 1000, $amount));

        header('Location: /');
    }
}
