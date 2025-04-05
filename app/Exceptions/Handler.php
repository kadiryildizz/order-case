<?php

namespace App\Exceptions;

use App\Libraries\Responder\Responder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @inheritdoc
     */
    public function render($request, Throwable $e): JsonResponse
    {
        $render = parent::render($request, $e);
        $httpStatusCode = $render->getStatusCode();

        if (method_exists($e, 'getStatusCode')) {
            $httpStatusCode = $e->getStatusCode();
        }

        if ($e instanceof ModelNotFoundException) {
            $modelName = class_basename($e->getModel());

            return Responder::error(
                errorCode: 404,
                errorMessage: "Not found",
                httpStatusCode: $httpStatusCode,
            );
        }

        return Responder::error(
            errorCode: $e->getCode(),
            errorMessage: $e->getMessage(),
            httpStatusCode: $httpStatusCode,
        );
    }
}
