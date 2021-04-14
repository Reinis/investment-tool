<?php

namespace InvestmentTool\Repositories;

use DateTime;
use Finnhub\Model\Quote;
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

        return $this->fetchAll($sql, $errorMessage, $symbol);
    }

    private function fetchAll(string $sql, string $errorMessage, string ...$args): Transactions
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($args);
        $results = $statement->fetchAll();

        if ($results === false) {
            throw new InvalidArgumentException($errorMessage);
        }

        $transactions = new Transactions();

        foreach ($results as $result) {
            $transactions->add(
                new Transaction(
                    $result->symbol,
                    $result->quote,
                    $result->amount,
                    $result->closed,
                    $result->id,
                    $result->closing_value,
                    $result->closing_date === null ? null : new DateTime($result->closing_date),
                    $result->quote_date === null ? null : new DateTime($result->quote_date),
                )
            );
        }

        return $transactions;
    }

    public function getAll(): Transactions
    {
        $sql = "select * from `transaction_log`;";
        $errorMessage = "No transactions found";

        return $this->fetchAll($sql, $errorMessage);
    }

    public function delete(int $id): void
    {
        $sql = "delete from `transaction_log` where id = ?;";
        $statement = $this->connection->prepare($sql);
        $statement->execute([$id]);
    }

    public function getSymbol($id): string
    {
        $sql = "select * from `transaction_log` where id = ?;";
        $errorMessage = "Transaction not found";

        return $this->fetch($sql, $errorMessage, $id)->getSymbol();
    }

    private function fetch(string $sql, string $errorMessage, string ...$args): Transaction
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($args);
        $result = $statement->fetch();

        if ($result === false) {
            throw new InvalidArgumentException($errorMessage);
        }

        var_dump($result);

        return new Transaction(
            $result->symbol,
            $result->quote,
            $result->amount,
            $result->closed,
            $result->id,
            $result->closing_value,
            $result->closing_date === null ? null : new DateTime($result->closing_date),
            $result->quote_date === null ? null : new DateTime($result->quote_date),
        );
    }

    public function close($id, Quote $quote): void
    {
        $sql = "update `transaction_log` set closed = true, closing_value = ?, closing_date = ? where id = ?;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(
            [
                $quote->getC() * 1000,
                (new DateTime('now'))->format('Y-m-d H:i:s'),
                $id,
            ]
        );
    }

    public function getActive(): Transactions
    {
        $sql = "select * from `transaction_log` where closed = false;";
        $errorMessage = "Could not get active investments";

        return $this->fetchAll($sql, $errorMessage);
    }

    public function getClosed(): Transactions
    {
        $sql = "select * from `transaction_log` where closed = true;";
        $errorMessage = "Could not get closed investments";

        return $this->fetchAll($sql, $errorMessage);
    }
}
