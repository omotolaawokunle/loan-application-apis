<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;

class LoggedIn
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
        $user = Storage::get('user.json');
        $user = json_decode($user);
        if(empty($user)){
            return response()->json(['error'=>true, 'message' => 'Unauthorized user!'], 401);
        }else{
            return $next($request);
        }
    }
}
