<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Param\StringWithParams;

class StringConverter extends Converter
{
    public function toString(): string
    {
        if ($this->arg === null) {
            return 'NULL';
        }
        return '\'' . mysqli_real_escape_string($this->mysqli, $this->arg) . '\'';
    }

    public function toStringWithParams(): StringWithParams
    {
        if ($this->arg === null) {
            return new StringWithParams('NULL');
        }
        $stringWithParams = new StringWithParams('?');
        $stringWithParams->pushParam($this->arg);

        return $stringWithParams;
    }
}
