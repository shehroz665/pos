<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($status,$message, $data = null, $statusCode = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
    public static function error($status,$message, $data = null, $statusCode = 500)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}
