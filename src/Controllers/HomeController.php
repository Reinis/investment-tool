<?php

namespace InvestmentTool\Controllers;

use Finnhub\Api\DefaultApi;
use Finnhub\ApiException;
use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Views\View;

class HomeController
{
    private TransactionRepository $repository;
    private DefaultApi $client;
    private View $view;

    public function __construct(TransactionRepository $repository, DefaultApi $client, View $view)
    {
        $this->repository = $repository;
        $this->client = $client;
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
            $quote = $this->client->quote($vars['symbol']);
        } catch (ApiException $e) {
            $message = "API request failed";
            echo $this->view->render('error', compact('message'));
            die();
        }

        echo $quote->getModelName() . ' ' . $quote->getC() . PHP_EOL;
    }
}
