<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait HttpResponse
{
    //
    protected function response($data,string $message = null,int $code = Response::HTTP_OK) :JsonResponse {
        return response()->json([
            "status"    => $code,
            "message"   => $message,
            "data"      => $data
        ],$code);
    }
}
