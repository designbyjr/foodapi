<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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

        if($exception->getStatusCode() == "404")
        {
            return response()->json(['errors'=>"The url is invalid, not found"], 404);
        }
        if($exception->getStatusCode() == "400")
        {
            return response()->json(['errors'=>"The request is Malformed"], 400);
        }
        if($exception->getStatusCode() == "500")
        {
            return response()->json(['errors'=>"Our server has lost its Guostto, please check and try again"], 500);
        }
        return parent::render($request, $exception);
    }
}
