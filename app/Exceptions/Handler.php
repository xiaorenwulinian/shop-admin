<?php

namespace App\Exceptions;

use App\Exceptions\JwtTokenException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof JwtTokenException) {
            return res_fail($exception->getMessage(),$exception->getCode());
        }

        if ($exception instanceof ValidationException) {
            return res_fail(array_first(array_collapse($exception->errors())));
        }
        $isAjax  = $request->ajax();
        if ($isAjax) {
            $m = $exception->getMessage();
            return res_fail($exception->getMessage());
        } else {
            $m = $exception->getMessage();
            dd($m);
            return parent::render($request, $exception);
        }



    }
}
