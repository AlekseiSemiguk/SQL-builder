<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\Converter;
use FpDbTest\BuildQueryService\Converter\UniversalConverter;

class UniversalParam extends Param
{
    public function getConverter($mysqli, $arg): Converter
    {
        return new UniversalConverter($mysqli, $arg);
    }
}
