<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\ArrayConverter;
use FpDbTest\BuildQueryService\Converter\Converter;

class ArrayParam extends Param
{
    public function getConverter($mysqli, $arg): Converter
    {
        return new ArrayConverter($mysqli, $arg);
    }
}
