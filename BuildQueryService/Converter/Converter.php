<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Param\StringWithParams;
use mysqli;

abstract class Converter
{
    public int|float|string|array|null $arg;
    public mysqli $mysqli;

    public function __construct(mysqli $mysqli, $arg)
    {
        $this->mysqli = $mysqli;
        $this->arg = $arg;
    }

    abstract public function toString(): string;

    abstract public function toStringWithParams(): StringWithParams;
}
