<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\Users;

class IsRegisterAllowed
{
    /**
     * 
     * Handle an incoming request.
     * 
     * This middleware checks if it is allowed to 
     * show thw register form. It will be allowed 
     * if there are not users registered
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if((new Users())->HasUsers()){
            // If the register route is not allowed go back
            return redirect()->back();
        }
        // The register route is allowed, continue
        return $next($request);
    }
}
