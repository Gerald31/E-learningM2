<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * @param $user
     * @param $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetResponse(Request $request)
    {
        $response = ['message' => "Password reset successful"];
        return response($response, 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetFailedResponse(Request $request)
    {
        $response = "Token Invalid";
        return response($response, 401);
    }
}
