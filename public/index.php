<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use InvestmentTool\Config;
use InvestmentTool\Repositories\MySQLTransactionRepository;
use InvestmentTool\Repositories\TransactionRepository;
use League\Container\Container;


$container = new Container();

$container->add(Config::class, Config::class)
    ->addArgument('.env');
$container->add(TransactionRepository::class, MySQLTransactionRepository::class)
    ->addArgument(Config::class);
