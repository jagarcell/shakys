<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Users;

class CheckUsersState
{
    /**
     * Handle an incoming request.
     * This middleware is used to determine if the authenticated user
     * is an 'admin' allowed to use the admin panel as home page or a user
     * that is allowed to user the user panel as home page.
     * If there is not an authenticated user then the middleware determines
     * if there are not users registered and redirects the request either to
     * Register(No users registered) or Login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $User = Auth::user();
        if(!is_null($User)){
            switch ($User->user_type) {
                case 'admin':
                    # code...
                    return $next($request); 
                    break;
                
                case 'user':
                    return redirect('/userdashboard');
                    break;

                case 'pickup':
                    return redirect('/pickupdashboard');
                    break;
                default:
                    # code...
                    break;
            }
        }
        else{
            try {
                //code...
                if((new Users())->HasUsers()){
                    return redirect('/home');
                }
                else{
                    return redirect('/register');
                }
            } catch (\Throwable $th) {
                //throw $th;
                $Message = $this->ErrorInfo($th);
                return redirect("/error/$Message");
            }
        }
    }
}
