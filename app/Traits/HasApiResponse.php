<?php

namespace App\Traits;

trait HasApiResponse
{
    public function json($data = [], $message = "success", $statusCode = 200)
    {
        return response()->json([
            "data" => $data,
            "message" => $message
        ], $statusCode);
    }
}
