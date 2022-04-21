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
                if($user->user_type == 'admin'){
                    return $next($request);
                }
                if($user->user_type == 'user'){
                    return redirect('/userdashboard');
                }
                if($user->user_type == 'pickup')
                {
                    return redirect('/pickupdashboard');
                }
/*                
                else{
                    return redirect('/unauth');
                }
*/
            } catch (\Exception $e) {
                return redirect('/');
            }
        }
        else{
            if(count($users) == 0){
                return $next($request);
            }
            else{
                // Get URLs
                $urlPrevious = url()->previous();
                $urlBase = url()->to('/');

                // Set the previous url that we came from to redirect to after successful login but only if is internal
                if(($urlPrevious != $urlBase . '/login') && (substr($urlPrevious, 0, strlen($urlBase)) === $urlBase)) {
                    session()->put('url.intended', $urlPrevious);
                }

                return redirect('/login');
            }
        }
    }
}
