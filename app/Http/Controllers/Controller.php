<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function json($data = [], $code = 200)
    {
        return response()->json($data, $code);
    }

    public function jsonException(\Exception $e, $code = 500, $message = 'An error occurred')
    {
        $code = $e->getCode() ?: $code;
        $message = $e->getMessage() ?: $message;

        return response()->json([
            'message' => $e->getMessage(),
        ], $code);
    }
}
