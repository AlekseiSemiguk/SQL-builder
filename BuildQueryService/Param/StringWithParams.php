<?php
namespace FpDbTest\BuildQueryService\Param;

class StringWithParams
{
    public string $string;
    public array $params;

    public function __construct($string)
    {
        $this->string = $string;
        $this->params = [];
    }

    public function pushParam($param): void
    {
        if (is_array($param)){
            $this->params = array_merge($this->params, $param);
        } else {
            $this->params[] = $param;
        }
    }
}
