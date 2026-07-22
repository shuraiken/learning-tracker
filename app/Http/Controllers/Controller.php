<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class Controller
{
    public function json($data, $message = "Success", $code = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $code);
    }

    public function jsonException(\Exception $e, $message = "Something went wrong", $code = 500)
    {
        if ($code === null) {
            $code = match (true) {
                $e instanceof HttpExceptionInterface => $e->getStatusCode(),
                $e instanceof ModelNotFoundException => 404,
                $e instanceof ValidationException => 422,
                $e instanceof \Illuminate\Auth\AuthenticationException => 401,
                $e instanceof \Illuminate\Auth\Access\AuthorizationException => 403,
                default => 500,
            };
        }

        $outMessage = $e->getMessage() !== '' ? $e->getMessage() : $message;

        return response()->json(['message' => $outMessage], $code);
    }
}
