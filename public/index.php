<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use FastRoute\RouteCollector;
use InvestmentTool\Config;
use InvestmentTool\Controllers\HomeController;
use InvestmentTool\Repositories\MySQLTransactionRepository;
use InvestmentTool\Repositories\TransactionRepository;
use InvestmentTool\Views\TwigView;
use InvestmentTool\Views\View;
use League\Container\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$container = new Container();

$container->add(Config::class, Config::class)
    ->addArgument('.env');
$container->add(TransactionRepository::class, MySQLTransactionRepository::class)
    ->addArgument(Config::class);

$container->add(FilesystemLoader::class, FilesystemLoader::class)
    ->addArgument(__DIR__ . '/../src/Views/twig');
$container->add(Environment::class, Environment::class)
    ->addArgument(FilesystemLoader::class)
    ->addArgument(
        [
            'cache' => __DIR__ . '/../twig_cache',
            'auto_reload' => true,
        ]
    );
$container->add(View::class, TwigView::class)
    ->addArgument(Environment::class);

$container->add(HomeController::class, HomeController::class)
    ->addArgument(TransactionRepository::class)
    ->addArgument(View::class);


$dispatcher = FastRoute\SimpleDispatcher(
    function (RouteCollector $r) {
        $r->addRoute('GET', '/', [HomeController::class, 'index']);

        $r->addRoute('POST', '/delete/{id:\d+}', [HomeController::class, 'delete']);

        $r->addRoute('GET', '/edit/{id:\d+}', [HomeController::class, 'edit']);
    }
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->get($class)->$method($vars);
        break;
}
