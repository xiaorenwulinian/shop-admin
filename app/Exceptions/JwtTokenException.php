<?php

namespace App\Exceptions;

use Exception;

class JwtTokenException extends Exception
{
    protected $code = 403;
}
