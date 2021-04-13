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
        $context = [
            'transactions' => $this->repository->getAll(),
        ];

        echo $this->view->render('home', $context);
    }

    public function delete(array $vars): void
    {
        $context = [
            'message' => "Not implemented",
        ];

        echo $this->view->render('error', $context);
    }
}
