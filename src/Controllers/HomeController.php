<?php

namespace InvestmentTool\Controllers;

use Finnhub\ApiException;
use InvestmentTool\Repositories\StockRepository;
use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Views\View;

class HomeController
{
    private TransactionRepository $repository;
    private StockRepository $stockRepository;
    private View $view;

    public function __construct(TransactionRepository $repository, StockRepository $stockRepository, View $view)
    {
        $this->repository = $repository;
        $this->stockRepository = $stockRepository;
        $this->view = $view;
    }

    public function index(): void
    {
        $transactions = $this->repository->getAll();

        echo $this->view->render('home', compact('transactions'));
    }

    public function delete(array $vars): void
    {
        $message = "Not implemented";

        echo $this->view->render('error', compact('message'));
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
}
