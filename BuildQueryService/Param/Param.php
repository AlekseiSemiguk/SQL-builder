<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\Converter;
use FpDbTest\BuildQueryService\SkipArg;
use mysqli;

abstract class Param
{
    public string $string;
    public int $firstSymbolPosition;
    public int|float|string|array|null|SkipArg $arg;
    public string|SkipArg|StringWithParams $queryArg;
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli, $string)
    {
        $this->mysqli = $mysqli;
        $this->string = $string;
    }

    public function setFirstSymbolPosition($position): void
    {
        $this->firstSymbolPosition = $position;
    }

    public function bindArg($arg): void
    {
        $this->arg = $arg;
        if ($arg instanceof SkipArg) {
            $this->queryArg = $arg;
        } else {
            $converter = $this->getConverter($this->mysqli, $arg);
            if (defined('USE_PDO') && USE_PDO === 1) {
                $this->queryArg = $converter->toStringWithParams();
            } else {
                $this->queryArg = $converter->toString();
            }
        }
    }

    abstract public function getConverter($mysqli, $arg): Converter;
}
