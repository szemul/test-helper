<?php
declare(strict_types=1);

namespace Szemul\TestHelper\Database;

use Szemul\Database\Factory\MysqlFactory;
use Szemul\Database\Result\QueryResult;
use Szemul\TestHelper\Database\Initializer\InitializerInterface;

class DatabaseHandler
{
    public function __construct(protected MysqlFactory $factory, protected InitializerInterface $initializer)
    {
    }

    public function initialize(): void
    {
        $this->initializer->initialize();
    }

    /** @param array<string,int|float|string> $params */
    public function runQuery(string $query, array $params): QueryResult
    {
        return $this->factory->getReadWrite()->query($query, $params);
    }

    /** @param array<string,int|float|string> $row */
    public function createRow(string $table, array $row): void
    {
        $sets  = [];

        foreach ($row as $key => $value) {
            $sets[] = $key . '= :' . $key;
        }

        $query = '
            INSERT INTO
                ' . $table . '
            SET
                ' . implode(', ', $sets) . '
        ';

        $this->runQuery($query, $row);
    }

    /** @param array<string,int|float|string>[] $rows */
    public function populateMultipleRows(string $table, array $rows): void
    {
        array_map(fn (array $row) => $this->createRow($table, $row), $rows);
    }
}
