<?php

namespace App\Exceptions;

use Exception;

class FailException extends BaseException
{
    protected $statusCode = 400;

    public function __construct($message = 'Fail.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code);
    }
}
