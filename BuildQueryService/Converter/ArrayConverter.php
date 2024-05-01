<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Exceptions\UnexpectedArgTypeException;
use FpDbTest\BuildQueryService\Param\StringWithParams;

class ArrayConverter extends Converter
{
    public function toString(): string
    {
        $this->validateArg();

        $string = '';
        if (array_is_list($this->arg)) {
            foreach ($this->arg as $value) {
                $string .= (new UniversalConverter($this->mysqli, $value))->toString() . ', ';
            }
            //return '`' . implode('`, `', $this->arg). '`';
        } else {
            foreach ($this->arg as $key => $value) {
                //$string .= '`' . $key . '\' = \'' . ($value === null ? 'NULL' : $value) . '\\\', ';
                $string .= '`' . $key . '` = ' . (new UniversalConverter($this->mysqli, $value))->toString() . ', ';
            }
        }
        return mb_substr($string, 0, -2);
    }

    public function toStringWithParams(): StringWithParams
    {
        $this->validateArg();

        $stringWithParams = new StringWithParams('');
        if (array_is_list($this->arg)) {
            foreach ($this->arg as $value) {
                $stringWithParamsElement = (new UniversalConverter($this->mysqli, $value))->toStringWithParams();
                $stringWithParams->string .= $stringWithParamsElement->string . ', ';
                $stringWithParams->pushParam($stringWithParamsElement->params);
            }
            //return '`' . implode('`, `', $this->arg). '`';
        } else {
            foreach ($this->arg as $key => $value) {
                $stringWithParamsElement = (new UniversalConverter($this->mysqli, $value))->toStringWithParams();
                //$stringWithParams->string .= $stringWithParamsElement->string . ', ';
                //$string .= '`' . $key . '\' = \'' . ($value === null ? 'NULL' : $value) . '\\\', ';
                $stringWithParams->string .= '`' . $key . '` = ' . $stringWithParamsElement->string . ', ';
                $stringWithParams->pushParam($stringWithParamsElement->params);
            }
        }
        $stringWithParams->string = mb_substr($stringWithParams->string, 0, -2);
        return $stringWithParams;
    }

    private function validateArg(): void
    {
        if (!is_array($this->arg)) {
            throw new UnexpectedArgTypeException();
        }
    }
}
