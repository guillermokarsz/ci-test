<?php

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private $connection;

    protected function setUp(): void
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'ci_test';
        $user = getenv('DB_USER') ?: 'ci_user';
        $password = getenv('DB_PASSWORD') ?: 'ci_password';

        $this->connection = pg_connect(
            "host=$host port=$port dbname=$dbname user=$user password=$password"
        );
    }

    public function testDatabaseConnectionIsValid(): void
    {
        $this->assertNotFalse(
            $this->connection,
            'Should be able to connect to PostgreSQL'
        );
    }

    public function testCanRunSimpleQuery(): void
    {
        $result = pg_query($this->connection, 'SELECT 1 as value');
        $this->assertNotFalse($result, 'Should be able to run a simple query');

        $row = pg_fetch_assoc($result);
        $this->assertEquals('1', $row['value'], 'Query should return expected value');
    }

    protected function tearDown(): void
    {
        if ($this->connection) {
            pg_close($this->connection);
        }
    }
}