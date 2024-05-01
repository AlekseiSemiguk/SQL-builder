<?php
namespace FpDbTest\BuildQueryService\Exceptions;

use Exception;

class UnexpectedArgTypeException extends Exception
{
    function __construct()
    {
        parent::__construct();
        $this -> message = "Unexpected type of argument";
    }
}
