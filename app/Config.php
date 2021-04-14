<?php

namespace InvestmentTool;

use Dotenv\Dotenv;

class Config
{
    private const DB_DSN = 'INVESTMENT_TOOL_DB_DSN';
    private const DB_USER = 'INVESTMENT_TOOL_DB_USER';
    private const DB_PASSWORD = 'INVESTMENT_TOOL_DB_PASSWORD';
    private const API_KEY = 'FINNHUB_API_KEY';

    private string $dsn;
    private string $user;
    private string $pass;
    private string $key;

    public function __construct(string $filename = '.env')
    {
        $this->loadDBConfig($filename);
    }

    private function loadDBConfig(string $filename): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../', $filename);
        $dotenv->load();
        $dotenv->required([self::DB_DSN, self::DB_USER, self::DB_PASSWORD]);

        $this->dsn = $_ENV[self::DB_DSN];
        $this->user = $_ENV[self::DB_USER];
        $this->pass = $_ENV[self::DB_PASSWORD];
        $this->key = $_ENV[self::API_KEY];
    }

    public function getDsn(): string
    {
        return $this->dsn;
    }

    public function getDBUsername(): string
    {
        return $this->user;
    }

    public function getDBPassword(): string
    {
        return $this->pass;
    }

    public function getApiKey(): string
    {
        return $this->key;
    }
}
