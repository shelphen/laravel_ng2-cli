<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
        if($request->wantsJson()) {
            if(env('API_MODE', 'path') == 'path') {
                if($request->is('api/*') || $request->is('api')) {
                    return $this->handleApi($request, $exception);
                }
            } else if(env('API_MODE', 'subdomain') == 'subdomain') {
                if($request->getHost() == 'api.'.env("APP_DOMAIN")) {
                    return $this->handleApi($request, $exception);
                }
            } else {
                throw new Exception('invalid API_MODE setting in environment file.');
            }
         }

        return $this->handleWeb($request, $exception);
    }

    /**
     * Handle web request exception
     *
     * @param $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response
     */
    private function handleWeb($request, Exception $exception) {

        if($exception instanceof NotFoundHttpException) {
            // redirect 404 error (HTTP not found) to bootstrap.blade.php (let ng2 handle itself)
            return response()->view('bootstrap')->header('Content-Type', 'text/html');
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle api request exception
     *
     * @param $request
     * @param Exception $exception
     * @return \Illuminate\Http\Response
     */
    private function handleApi($request, Exception $exception) {
        if($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(['token_expired'], $exception->getStatusCode());
        } else if($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
            return response()->json(['token_invalid'], $exception->getStatusCode());
        } else if($exception instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
            return response()->json(['token_absent'], $exception->getStatusCode());
        }

        return response()->json(['invalid_request'], $exception->getStatusCode());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
