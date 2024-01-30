<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{

    protected function success($code = 200 ,$data = null, $message = null):JsonResponse
    {

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }


    protected function error($code , $data = null ,$message = null):JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
