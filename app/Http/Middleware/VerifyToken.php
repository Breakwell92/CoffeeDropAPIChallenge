<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('token');

        if(!$token){
            return response()->json("Please ensure you include your token in your request headers using the key 'token'", 401);
        }

        if($token != 'imagine this was an oauth token'){
            return response()->json('The token you have provided is incorrect. Please check it and try again', 401);
        }

        return $next($request);
    }
}
