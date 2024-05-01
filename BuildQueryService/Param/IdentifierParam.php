<?php
namespace FpDbTest\BuildQueryService\Param;

use FpDbTest\BuildQueryService\Converter\Converter;
use FpDbTest\BuildQueryService\Converter\IdentifierConverter;

class IdentifierParam extends Param
{
    public function getConverter($mysqli, $arg): Converter
    {
        return new IdentifierConverter($mysqli, $arg);
    }
}
