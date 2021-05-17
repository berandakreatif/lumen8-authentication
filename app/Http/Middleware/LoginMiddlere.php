<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class ExampleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('token')) {
            $check =  User::where('token', $request->input('token'))->first();

            if (!$check) {
                return response('Token Invalid.', 401);
            } else {
                return $next($request);
            }
        } else {
            return response('Please Enter Token.', 401);
        }
    }
}
