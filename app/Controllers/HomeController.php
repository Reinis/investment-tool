<?php

namespace InvestmentTool\Controllers;

use Finnhub\ApiException;
use InvalidArgumentException;
use InvestmentTool\Services\AssetService;
use InvestmentTool\Services\FundsService;
use InvestmentTool\Services\QuoteService;
use InvestmentTool\Services\TransactionService;
use InvestmentTool\Views\View;

class HomeController
{
    private AssetService $assetService;
    private QuoteService $quoteService;
    private TransactionService $transactionService;
    private FundsService $fundsService;
    private View $view;

    public function __construct(
        AssetService $assetService,
        QuoteService $quoteService,
        TransactionService $transactionService,
        FundsService $fundsService,
        View $view
    )
    {
        $this->assetService = $assetService;
        $this->quoteService = $quoteService;
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

        try {
            $this->transactionService->close($id);
        } catch (InvalidArgumentException $e) {
            $message = "Invalid action";
            return $this->view->render('error', compact('message'));
        }

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

        $quote = $this->quoteService->quote($symbol);
        $transactions = $this->transactionService->getAll();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('home', compact('symbol', 'quote', 'transactions', 'availableFunds'));
    }

    public function quote(array $vars): string
    {
        try {
            $quote = $this->quoteService->quote($vars['symbol']);
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

        try {
            $this->transactionService->add($symbol, $amount);
        } catch (InvalidArgumentException $e) {
            $message = "Not enough funds";
            return $this->view->render('error', compact('message'));
        }

        header('Location: /');
    }

    public function overview(): string
    {
        $symbols = $this->assetService->get();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('overview', compact('symbols', 'availableFunds'));
    }
}
