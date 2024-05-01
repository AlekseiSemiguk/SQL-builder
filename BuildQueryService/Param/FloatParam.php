<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\Converter;
use FpDbTest\BuildQueryService\Converter\FloatConverter;

class FloatParam extends Param
{
    public function getConverter($mysqli, $arg): Converter
    {
        return new FloatConverter($mysqli, $arg);
    }
}
