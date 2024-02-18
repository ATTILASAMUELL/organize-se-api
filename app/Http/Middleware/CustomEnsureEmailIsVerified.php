<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class CustomEnsureEmailIsVerified extends BaseEnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if($request->is('api/register') || $request->is('/api/email/resend') || strpos($request->getPathInfo(), '/api/email/verify/') !== false ) {
            return $next($request);
        }
        
        // Verifica se o cabeçalho Authorization está presente na requisição
        if ($request->header('Authorization')) {
            if ($request->user() || ($request->user() instanceof MustVerifyEmail &&  $request->user()->hasVerifiedEmail())) {
                return $next($request);
            }
        }

        $exist = false;
        
        $userModel = User::where('email',$request->email)->first();

        if(!empty($userModel->name)) {
            if($userModel->hasVerifiedEmail()) {
                return $next($request);
            }
            $exist = true;
            $userModel->sendEmailVerificationNotification();
        }else {
            return response()->json(['error' => 'Error'], 404);
        }
        return response()->json(['error' => 'Email not verified.' ,'emailValidation' => false, 'exist' => $exist], 403);
    }
}