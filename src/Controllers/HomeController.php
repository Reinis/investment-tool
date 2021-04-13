<?php

namespace InvestmentTool\Controllers;

use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Views\View;

class HomeController
{
    private TransactionRepository $repository;
    private View $view;

    public function __construct(TransactionRepository $repository, View $view)
    {
        $this->repository = $repository;
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
}
