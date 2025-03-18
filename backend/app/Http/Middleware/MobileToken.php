<?php

namespace App\Http\Middleware;

use App\Models\Responder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Symfony\Component\HttpFoundation\Response;

class MobileToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // sleep(5);
        // Log::info($request->getContent());
        // $data = json_decode($request->getContent(),true);
        // $token = $data['data']['token'] ?? "";
        // $responder = Responder::where('token',$token)->first();
        // if(!$responder){
        //     return  FacadesResponse::json([
        //         "status"    => "Error",
        //         "result"    => json_encode($responder)
        //     ]);
        // }
        return $next($request);
    }
}
