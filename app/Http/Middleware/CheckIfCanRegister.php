<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Users;

class CheckIfCanRegister
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
        $user = Auth::user();
        $users = (new Users())->where('id', '>', -1)->get();

        if(!is_null($user))
        {
            try {
                if($user->user_type == 'admin' || count($users) == 0){
                    return $next($request);
                }
                else{
                    return redirect('/unauth');
                }
            } catch (\Exception $e) {
                return redirect('/');
            }
        }
        else{
            return redirect('/login');
        }
    }
}
