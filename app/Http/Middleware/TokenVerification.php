<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class TokenVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->input('token') == null) {
            return response()->json(['error' => 'Invalid Token'], 401);
        }

        $token = $request->input('token');
        $userToken = User::select('api_token_date')->where('api_token', $token)->first();

        if(isset($userToken->api_token_date)) {
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:m:i', $userToken->api_token_date);
            $currentDate = \Carbon\Carbon::now();
            $date->modify('+1 day');

            if($date->lt($currentDate)) {
                return response()->json(['error' => 'Token Expired'], 401);
            }
        } else {
            return response()->json(['error' => 'Token Not Found'], 401);
        }

        return $next($request);
    }
}
