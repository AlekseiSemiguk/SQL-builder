<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Param\StringWithParams;

class BoolConverter extends Converter
{
    public function toString(): string
    {
        if ($this->arg === true) {
            return '1';
        } else {
            return '0';
        }
    }

    public function toStringWithParams(): StringWithParams
    {
        return new StringWithParams($this->toString());
    }
}
