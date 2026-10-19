<?php

namespace App\Helper;


class ApiResponse
{
    public function success($data, $message, $code) {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }
}
