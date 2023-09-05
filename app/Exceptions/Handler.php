<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //  AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        //ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function(Exception $e, $request) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson() ) {
                return $this->handleException($e);
            }
        });
    }
    public function handleException(Throwable $e): \Illuminate\Http\JsonResponse
    {
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
            $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
            return $this->errorResponse($message,'ERROR.HTTP_EXCEPTION', $code);
        } else if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("Does not exist any instance of {$model} with the given id",'ERROR.INTERNAL_EXCEPTION', 404);
        } else if ($e instanceof AuthorizationException) {
            return $this->errorResponse($e->getMessage(),'ERROR.ACCESS_FORBIDDEN', 403);
        } else if ($e instanceof TokenMismatchException) {
            return $this->errorResponse($e->getMessage(),'ERROR.UNAUTHORIZED', 401);
        } else if ($e instanceof AuthenticationException) {
            return $this->errorResponse($e->getMessage(),'ERROR.UNAUTHORIZED', 401);
        } else if ($e instanceof ValidationException) {
            $errors = $e->validator->errors();
            return $this->errorResponse('Parameter masukan tidak valid','ERROR.UNPROCESSABLE_ENTITY', 422,$errors);
        } else {
            if (config('app.debug')=='true'){
                return $this->errorResponse($e->getMessage(),'ERROR.INTERNAL_SERVER_ERROR',  500);
            } else {
                return $this->errorResponse('internal server error','ERROR.INTERNAL_SERVER_ERROR',  500);
            }

        }
    }
    public function errorResponse($message,$status, $code = 400,$errors = array())
    {
        $respon['respon_status'] = array('status' => $status, 'code' =>  $code, 'message' => $message);
        $respon['errors'] = $errors;

        return response()->json($respon, $code);
    }
}
