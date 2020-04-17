<?php

namespace App\Exceptions;

use Exception;

class JwtTokenException extends Exception
{
    protected $code = 403;

    protected $message = "token 失效， 请重新登陆！";
}
