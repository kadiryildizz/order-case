<?php

namespace App\Libraries\Responder;



use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class Responder
{
    /**
     * Base response model.
     *
     * @var array<string, mixed>
     */
    protected static array $responseModel = [
        'status' => null,
        'error' => null,
        'data' => null,
    ];

    /**
     * Returns success response.
     *
     * @param ?array $data
     * @return JsonResponse
     */
    public static function success(
        ?array $data = null,
        int $httpStatusCode = 200,
    ): JsonResponse {
        $response = self::$responseModel;

        $response['status'] = $httpStatusCode;
        $response['data'] = $data;

        return Response::json($response);
    }

    /**
     * Returns error response.
     *
     * @param ?int $errorCode
     * @param ?string $errorMessage
     * @param int $httpStatusCode
     * @return JsonResponse
     */
    public static function error(
        ?int $errorCode = null,
        ?string $errorMessage = null,
        int $httpStatusCode = 400,
    ): JsonResponse {
        $response = self::$responseModel;

        $response['status'] = $httpStatusCode;

        $response['error'] = [
            'code' => $errorCode,
            'message' => $errorMessage
        ];

        return Response::json($response, $httpStatusCode);
    }

    /**
     * Returns base response model.
     *
     * @return array<string, mixed>
     */
    public static function getResponseModel(): array
    {
        return self::$responseModel;
    }
}
