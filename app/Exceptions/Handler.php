<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\ViewNotFoundException; // Import the ViewNotFoundException class

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

        // Handle ModelNotFoundException (404 for missing models)
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404); // Return custom 404 page
        }

        // Handle NotFoundHttpException (404 for missing routes)
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404); // Return custom 404 page
        }

        // Default exception rendering
        return parent::render($request, $exception);
    }
}

