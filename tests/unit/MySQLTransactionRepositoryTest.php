<?php

namespace unit;

use Codeception\Test\Unit;
use InvestmentTool\Config;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\MySQLTransactionRepository;
use PDO;
use PDOException;
use UnitTester;

class MySQLTransactionRepositoryTest extends Unit
{
    private const CONFIG_FILENAME = '.env.test';

    private static ?MySQLTransactionRepository $repository;
    private static ?PDO $connection;

    protected UnitTester $tester;

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
        $sql = "drop table if exists `investments_test`.`transaction_log`;";
        self::$connection->exec($sql);

        $sql = "create table `investments_test`.`transaction_log` like `investments`.`transaction_log`;";
        self::$connection->exec($sql);
    }

    public function testAddTransaction(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);
        self::$repository->add($transaction);

        $this->tester->seeInDatabase('transaction_log', ['symbol' => 'XYZ', 'quote' => 42, 'amount' => 5]);
    }
}
