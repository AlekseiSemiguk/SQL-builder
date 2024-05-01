<?php
namespace FpDbTest\BuildQueryService\Converter;

use FpDbTest\BuildQueryService\Exceptions\InvalidIdentifierException;
use FpDbTest\BuildQueryService\Exceptions\UnexpectedArgTypeException;
use FpDbTest\BuildQueryService\Param\StringWithParams;

class IdentifierConverter extends Converter
{
    public function toString(): string
    {
        if (!$this->arg) {
            throw new UnexpectedArgTypeException();
        }

        if (is_array($this->arg)) {
            $string = '';
            foreach ($this->arg as $value) {
                if (!$this->checkWhiteList($value)) {
                    throw new InvalidIdentifierException();
                }
                $string .= '`' . str_replace("`","``", $value) . '`, ';
            }
            return mb_substr($string, 0, -2);
        } else
            if (!$this->checkWhiteList($this->arg)) {
                throw new InvalidIdentifierException();
            }
            return '`' . str_replace("`","``", $this->arg) . '`';
    }

    public function toStringWithParams(): StringWithParams
    {
        return new StringWithParams($this->toString());
    }

    private function checkWhiteList($arg): bool
    {
        // возможна реализация проверки идентификаторов по белому листу, доступному пользователю
        return true;
    }
}
