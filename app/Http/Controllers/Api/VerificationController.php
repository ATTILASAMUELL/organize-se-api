<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Code\ApiCode;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class VerificationController extends Controller {

    /**
     * Verify email
     *
     * @param $user_id
     * @param Request $request
     * @return \Illuminate\Support\Facades\Redirect
     */
    public function verify($user_id, Request $request) {
        if (! $request->hasValidSignature()) {
            return $this->respondUnAuthorizedRequest(ApiCode::INVALID_EMAIL_VERIFICATION_URL);
        }

        $user = User::findOrFail($user_id);
        
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $redirectUrl = env('REDIRECT_URL');
        return Redirect::away($redirectUrl);
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(['error' => "Email already verified"], ApiCode::EMAIL_ALREADY_VERIFIED);
        }

        auth()->user()->sendEmailVerificationNotification();

        return response()->json(['error' => "Email verification link sent on your email id"], 200);
    }
}