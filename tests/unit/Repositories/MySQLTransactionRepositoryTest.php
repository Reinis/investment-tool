<?php

declare(strict_types=1);

namespace unit\Repositories;

use InvalidArgumentException;
use InvestmentTool\Config;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\MySQLTransactionRepository;
use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
use stdClass;

class MySQLTransactionRepositoryTest extends TestCase
{
    private const CONFIG_FILENAME = '.env.test';

    private static ?MySQLTransactionRepository $repository;
    private static ?PDO $connection;

    public static function setUpBeforeClass(): void
    {
        $config = new Config(self::CONFIG_FILENAME);

        self::$repository = new MySQLTransactionRepository($config);

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            self::$connection = new PDO(
                $config->getDsn(),
                $config->getDBUsername(),
                $config->getDBPassword(),
                $options
            );
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        // Reset tables in the test database
        self::resetTestDBTables();
    }

    public static function tearDownAfterClass(): void
    {
        self::resetTestDBTables();
        self::$repository = null;
        self::$connection = null;
    }

    private static function resetTestDBTables(): void
    {
        $sql = "drop table if exists `investments_test`.`transaction_log`;";
        self::$connection->exec($sql);

        $sql = "create table `investments_test`.`transaction_log` like `investments`.`transaction_log`;";
        self::$connection->exec($sql);
    }

    public function testAddTransaction(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);
        self::$repository->add($transaction);

        $sql = "select count(*) as count from `transaction_log`;";
        $result = $this->fetch($sql, "Error fetching results");

        self::assertEquals(1, $result->count);

        $sql = "select * from `transaction_log` where symbol = 'XYZ';";
        $result = $this->fetch($sql, "Transaction not found");

        self::assertEquals('XYZ', $result->symbol);
        self::assertEquals(42, $result->quote);
        self::assertEquals(5, $result->amount);
    }

    private function fetch(string $sql, string $errorMessage): stdClass
    {
        $result = self::$connection
            ->query($sql)
            ->fetch();

        if ($result === false) {
            throw new InvalidArgumentException($errorMessage);
        }

        return $result;
    }
}
