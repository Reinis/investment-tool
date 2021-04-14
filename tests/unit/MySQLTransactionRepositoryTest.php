<?php

namespace unit;

use Codeception\Test\Unit;
use DateTime;
use InvestmentTool\Config;
use InvestmentTool\Entities\Transaction;
use InvestmentTool\Repositories\MySQLTransactionRepository;
use PDO;
use UnitTester;

class MySQLTransactionRepositoryTest extends Unit
{
    private const CONFIG_FILENAME = '.env.test';

    protected UnitTester $tester;

    private ?MySQLTransactionRepository $repository;
    private ?PDO $connection;

    public function _before(): void
    {
        $config = new Config(self::CONFIG_FILENAME);

        $this->repository = new MySQLTransactionRepository($config);
        $this->connection = $this->getModule('Db')->dbhs['default'];

        $this->resetTestDBTables();
    }

    private function resetTestDBTables(): void
    {
        $sql = "drop table if exists `investments_test`.`transaction_log`;";
        $this->connection->exec($sql);

        $sql = "create table `investments_test`.`transaction_log` like `investments`.`transaction_log`;";
        $this->connection->exec($sql);
    }

    public function _after(): void
    {
        $this->resetTestDBTables();
    }

    public function testAddTransaction(): void
    {
        $transaction = new Transaction('XYZ', 42, 5);
        $this->repository->add($transaction);

        $this->tester->seeInDatabase('transaction_log', ['symbol' => 'XYZ', 'quote' => 42, 'amount' => 5]);
    }

    public function testDeleteTransaction(): void
    {
        $this->tester->haveInDatabase(
            'transaction_log',
            [
                'symbol' => 'XYZ',
                'quote' => 42,
                'quote_date' => (new DateTime('now'))->format('Y-m-d H:i:s'),
                'amount' => 5,
            ]
        );

        $this->tester->seeNumRecords(1, 'transaction_log');
        $this->repository->delete(1);
        $this->tester->seeNumRecords(0, 'transaction_log');
    }
}
