<?php
namespace FpDbTest\BuildQueryService;

class ConditionalBlock
{
    public string $string;
    public int $length;
    public int $firstSymbolPosition;
    public int $lastSymbolPosition;

    public function __construct($string, $firstSymbolPosition)
    {
        $this->string = $string;
        $this->firstSymbolPosition = $firstSymbolPosition;
        $this->length = strlen($string);
        $this->lastSymbolPosition = $firstSymbolPosition + $this->length - 1;
    }
}