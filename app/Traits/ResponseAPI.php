<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseAPI
{
    /**
     * Core of response
     *
     * @param array|string|null $message
     * @param mixed $data
     * @param integer $statusCode
     * @param boolean $isSuccess
     *
     * @return JsonResponse
     */
    public function coreResponse(array|string|null $message, mixed $data, int $statusCode, bool $isSuccess = true): JsonResponse
    {
        /* Check the params */
        if (!$message) {
            return response()->json([
                'res_status' => 'error',
                'res_data'   => 'Message is required'
            ], 500);
        }

        /* Send the response */
        if ($isSuccess) {
            return response()->json([
                'res_status'  => 'success',
                'res_message' => $message,
                'res_data'    => $data
            ], $statusCode);
        }

        return response()->json([
            'res_status'  => 'error',
            'res_message' => $message,
        ], $statusCode);
    }

    /**
     * Send any success response
     *
     * @param array|string $message
     * @param object|array|string $data
     * @param integer $statusCode
     *
     * @return JsonResponse
     */
    public function success(array|string $message, object|array|string $data = '', int $statusCode = 200): JsonResponse
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    /**
     * Send any error response
     *
     * @param array|string $message
     * @param integer $statusCode
     *
     * @return JsonResponse
     */
    public function error(array|string $message, int $statusCode = 500): JsonResponse
    {
        return $this->coreResponse($message, null, $statusCode, false);
    }

    /**
     * Send any fail response
     */
    public function fail(
        string $message = 'Estamos com problemas. Tente novamente mais tarde',
        int $statusCode = 500
    ): JsonResponse {
        return $this->coreResponse($message, null, $statusCode, false);
    }
}
