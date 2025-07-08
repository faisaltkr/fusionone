<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->uri()->path();
        if($uri == 'api/sales' || $uri == 'api/sales/{id}') {
           
            $clientId = $request->header('clientId');
             
            $client = User::where('company_reg_id', $clientId)->where('status',1)->first();
            // if (!$client) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Unauthorized client ID.'
            //     ], Response::HTTP_UNAUTHORIZED);
            // }
            return $next($request);
        }
        return $next($request);
       
    }
}
