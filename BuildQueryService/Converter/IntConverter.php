<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Param\StringWithParams;

class IntConverter extends Converter
{
    public function toString(): string
    {
        if ($this->arg === null) {
            return 'NULL';
        }
        return (string)(int)$this->arg;
    }

    public function toStringWithParams(): StringWithParams
    {
        return new StringWithParams($this->toString());
    }
}
