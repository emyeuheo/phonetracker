<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($request->ajax()) {
            $code = 500;
            $statusCode = 500;
            $message = 'Unknown error';

            if($this->isHttpException($e)) {
                $exception = $this->renderHttpException($e);
                if ($exception->getStatusCode() == 404) {
                    $code = 404;
                    $statusCode = 404;
                    $message = 'Not found';
                }

                if ($exception->getStatusCode() == 500) {
                    $code = 500;
                    $statusCode = 500;
                    $message = 'Something weird happen to the server';
                }
                echo $exception->getContent();
            } else {
                $code = (!empty($e->getCode())) ? $e->getCode() : $code;
                $message = (!empty($e->getMessage())) ? $e->getMessage() : $message;
            }


            return new Response(json_encode(array(
                'error' => array(
                    'code' => $code,
                    'message' => $message,
                    'trace' => $e->getFile().":".$e->getLine()
                )
            )), $statusCode, array('Content-Type' => 'application/json'));
        }

        return parent::render($request, $e);
    }
}
