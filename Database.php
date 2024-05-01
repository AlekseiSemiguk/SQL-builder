<?php

namespace FpDbTest;

use FpDbTest\BuildQueryService\SkipArg;
use FpDbTest\BuildQueryService\QueryString;
use mysqli;
use PDOException;

class Database implements DatabaseInterface
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function buildQuery(string $query, array $args = []): string
    {
        if (!defined('USE_PDO')) {
            define('USE_PDO', 0);
        }
        $queryString = new QueryString($this->mysqli, $query);
        $queryString->bindArguments($args);
        $queryString->cleanConditionalBlocks();
        $queryString->replaceParamsWithArgs();
        return $queryString->getResultString();

        // для экранирования строковых параметров используется mysqli_real_escape_string
        // дополнительно предложена реализация через PDO + prepare + execute, в этом случае в SQL-запросе строковые аргументы
        // заменены на "?", а параметры хранятся в $queryString->paramsPdo
        // ниже - пример выполнения запроса через PDO
        // для включения PDO поменять константу USE_PDO на строке 22, но тест в этом случае не будет
        // пройден, поскольку в SQL-строке вместо строковых переменных будет "?"
        $dbh = new \PDO('mysql:dbname=database;host=localhost;port=3306', 'root', 'password');
        $sth = $dbh->prepare($queryString->getResultString());
        foreach ($queryString->paramsPdo as $key => $value) {
            $sth->bindValue($key + 1, $value);
        }
        try {
            $sth->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $sth->debugDumpParams();
    }

    public function skip(): SkipArg
    {
        return new SkipArg();
    }
}
