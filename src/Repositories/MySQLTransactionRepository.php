<?php

namespace InvestmentTool\Repositories;

use InvalidArgumentException;
use InvestmentTool\Config;
use InvestmentTool\Entities\Collections\Transactions;
use InvestmentTool\Entities\Transaction;
use PDO;
use PDOException;

class MySQLTransactionRepository implements TransactionRepository
{
    private PDO $connection;

    public function __construct(Config $config)
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->connection = new PDO(
                $config->getDsn(),
                $config->getDBUsername(),
                $config->getDBPassword(),
                $options
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function add(Transaction $transaction): void
    {
        $sql = "insert into `transaction_log` (symbol, quote, quote_date, amount) values (?, ?, ?, ?);";
        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                $transaction->getSymbol(),
                $transaction->getQuote(),
                $transaction->getQuoteDate()->format('Y-m-d H:i:s'),
                $transaction->getAmount(),
            ]
        );
    }

    public function searchBySymbol(string $symbol): Transactions
    {
        $sql = "select * from `transaction_log` where symbol = ?;";
        $errorMessage = "Transaction with symbol '{$symbol}' not found";

        return $this->run($sql, $errorMessage, $symbol);
    }

    private function run(string $sql, string $errorMessage, string ...$args): Transactions
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($args);
        $results = $statement->fetchAll();

        if ($results === false) {
            throw new InvalidArgumentException($errorMessage);
        }

        $transactions = new Transactions();

        foreach ($results as $result) {
            $transactions->add(new Transaction($result->symbol, $result->quote, $result->amount));
        }

        return $transactions;
    }


    public function getAll(): Transactions
    {
        $sql = "select * from `transaction_log`;";
        $errorMessage = "No transactions found";

        return $this->run($sql, $errorMessage);
    }
}
