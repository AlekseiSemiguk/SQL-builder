<?php
namespace FpDbTest\BuildQueryService\Exceptions;

use Exception;

class InvalidIdentifierException extends Exception
{
    function __construct()
    {
        parent::__construct();
        $this -> message = "Invalid identifier";
    }
}
