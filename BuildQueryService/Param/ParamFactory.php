<?php
namespace FpDbTest\BuildQueryService\Param;

use mysqli;

class ParamFactory
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function getParam($string): Param
    {
        return match (substr($string,1,1)) {
            'd' => new IntParam($this->mysqli, $string),
            'f' => new FloatParam($this->mysqli, $string),
            'a' => new ArrayParam($this->mysqli, $string),
            '#' => new IdentifierParam($this->mysqli, $string),
            default => new UniversalParam($this->mysqli, $string)
        };
    }
}
