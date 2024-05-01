<?php
namespace FpDbTest\BuildQueryService\Exceptions;

use Exception;

class NumberArgsException extends Exception
{
    function __construct()
    {
        parent::__construct();
        $this -> message = "Incorrect number of arguments";
    }
}
