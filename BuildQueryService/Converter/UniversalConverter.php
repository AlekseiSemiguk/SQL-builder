<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Exceptions\UnexpectedArgTypeException;
use FpDbTest\BuildQueryService\Param\StringWithParams;

class UniversalConverter extends Converter
{
    public function toString(): string
    {
        if ($this->arg === null) {
            return 'NULL';
        }

        return $this->toStringHelper('toString');
    }

    public function toStringWithParams(): StringWithParams
    {
        if ($this->arg === null) {
            return new StringWithParams('NULL');
        }
        return $this->toStringHelper('toStringWithParams');
    }

    private function toStringHelper($funcName): string|StringWithParams
    {
        if (is_int($this->arg)) {
            return (new IntConverter($this->mysqli, $this->arg))->$funcName();
        }

        if (is_float($this->arg)) {
            return (new FloatConverter($this->mysqli, $this->arg))->$funcName();
        }

        if (is_bool($this->arg)) {
            return (new BoolConverter($this->mysqli, $this->arg))->$funcName();
        }

        if (is_string($this->arg)) {
            return (new StringConverter($this->mysqli, $this->arg))->$funcName();
        }

        throw new UnexpectedArgTypeException();
    }
}
