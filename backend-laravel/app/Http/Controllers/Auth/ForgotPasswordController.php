<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ForgotPasswordController extends Controller
{
    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetLinkResponse(Request $request)
    {
        $response = ['message' => "Password reset email sent"];
        return response($response, 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    protected function sendResetLinkFailedResponse(Request $request)
    {
        $response = "Email could not be sent to this email address";
        return response($response, 500);
    }
}
