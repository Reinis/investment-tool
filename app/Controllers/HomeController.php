<?php


namespace InvestmentTool\Controllers;


use Finnhub\ApiException;
use InvalidArgumentException;
use InvestmentTool\Services\AssetService;
use InvestmentTool\Services\FundsService;
use InvestmentTool\Services\QuoteService;
use InvestmentTool\Services\TransactionService;
use InvestmentTool\Validation\FailedValidationException;
use InvestmentTool\Validation\ValidatorInterface;
use InvestmentTool\Views\View;


class HomeController
{
    private ValidatorInterface $validator;
    private AssetService $assetService;
    private QuoteService $quoteService;
    private TransactionService $transactionService;
    private FundsService $fundsService;
    private View $view;

    public function __construct(
        ValidatorInterface $validator,
        AssetService $assetService,
        QuoteService $quoteService,
        TransactionService $transactionService,
        FundsService $fundsService,
        View $view
    )
    {
        $this->validator = $validator;
        $this->assetService = $assetService;
        $this->quoteService = $quoteService;
        $this->transactionService = $transactionService;
        $this->fundsService = $fundsService;
        $this->view = $view;
    }

    public function index(): string
    {
        $assets = $this->assetService->get();
        $transactions = $this->transactionService->getAll();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('home', compact('assets', 'transactions', 'availableFunds'));
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
        try {
            $this->validator->validate('quote', $_POST);
        } catch (FailedValidationException $e) {
            $message = "Invalid query";
            return $this->view->render('error', compact('message'));
        }

        $symbol = trim($_POST['symbol']);
        $quote = $this->quoteService->quote($symbol);
        $assets = $this->assetService->get();
        $transactions = $this->transactionService->getAll();
        $availableFunds = $this->fundsService->getAvailableFunds();

        return $this->view->render('home', compact('symbol', 'quote', 'assets', 'transactions', 'availableFunds'));
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
        try {
            $this->validator->validate('buy', $_POST);
        } catch (FailedValidationException $e) {
            $message = "Invalid input";
            return $this->view->render('error', compact('message'));
        }

        $symbol = $_POST['symbol'];
        $amount = $_POST['amount'];

        try {
            $this->transactionService->add($symbol, $amount);
        } catch (InvalidArgumentException $e) {
            $message = "Not enough funds";
            return $this->view->render('error', compact('message'));
        }

        header('Location: /');
    }
}
