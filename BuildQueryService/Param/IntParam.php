<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\Converter;
use FpDbTest\BuildQueryService\Converter\IntConverter;

class IntParam extends Param
{
    public function getConverter($mysqli, $arg): Converter
    {
        return new IntConverter($mysqli, $arg);
    }
}
