<?php declare(strict_types=1);

namespace Doctrine\DBAL\Driver\Swoole\Coroutine\PostgreSQL;

use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\Result as ResultInterface;
use Swoole\Coroutine\PostgreSQL;

class Result implements ResultInterface
{
    private PostgreSQL $connection;
    private $result;

    public function __construct(PostgreSQL $connection, $result)
    {
        $this->connection = $connection;
        $this->result = $result;
    }

    public function fetchNumeric()
    {
        return $this->result->fetchArray();
    }

    public function fetchAssociative()
    {
        return $this->result->fetchAssoc();
    }

    public function fetchOne()
    {
        $result = $this->result->fetchRow($this->result);
        return $result ? $result[0] : false;
    }

    public function fetchAllNumeric(): array
    {
        return $this->result->fetchAll($this->result, SW_PGSQL_NUM) ?: [];
    }

    public function fetchAllAssociative(): array
    {
        return $this->result->fetchAll($this->result, SW_PGSQL_ASSOC) ?: [];
    }

    public function fetchFirstColumn(): array
    {
        return array_column($this->fetchAllNumeric(), 0);
    }

    public function rowCount(): int
    {
        return $this->result->numRows();
    }

    public function columnCount(): int
    {
        return $this->result->fieldCount();
    }

    public function free(): void
    {
        $this->result = null;
    }


}
