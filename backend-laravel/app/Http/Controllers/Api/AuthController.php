<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser;
use App\Services\User\UserServices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * User login API method
     *
     * @param LoginUser $request
     * @return Response
     */
    public function login(LoginUser $request): Response
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            if ($user->isActive()) {
                $success['firstname']  = $user->firstname;
                $success['lastname']  = $user->lastname;
                $success['roles']  = $user->roles;
                $success['id']  = $user->id;
                $success['avatar']  = $user->avatar;
                $success['token'] = $user->createToken('accessToken')->accessToken;
                $response = response($success, Response::HTTP_OK);
            } else {
                $error['firstname']  = $user->firstname;
                $error['message']  = 'Votre compte n\'est pas encore activé, Consulter votre boîte email pour activé ce compte';
                $response = response($error, Response::HTTP_BAD_REQUEST);
            }
        } else {
            $error['message'] = 'Votre email ou mot de passe est incorrect';
            $response = response($error, Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    /**
     * @param UserServices $userServices
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllUsers(UserServices $userServices) {
        return response($userServices->getAllUsers(),200);
    }

    /**
     * @param string $token
     * @param UserServices $userServices
     * @return mixed
     */
    public function verifyUser(string $token, UserServices $userServices)
    {
        return $userServices->verifyToken($token);
    }
}
