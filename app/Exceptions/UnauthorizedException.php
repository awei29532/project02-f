<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends BaseException
{
    protected $statusCode = 401;

    public function __construct($message = 'Unauthorized.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code);
    }
}
