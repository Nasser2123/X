<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Resources\UserRescource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        return $this->success(200, new UserRescource($user), 'Please verify your email');
    }
    public function login(LoginRequest $request):JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return $this->error(403,null, 'The email or password you typed is incorrect. Please try again');
        }
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
                return $this->error(401, null,'Please verify your email');
            }
            $token =$user->createToken($user['user_name']);
            return $this->success(200
                ,[
                    "user" => new UserRescource($user),
                    "token" =>$token->plainTextToken,
                ],
                'Login successfully');
    }

    public function changePassword(ChangePasswordRequest $request ,User $user):JsonResponse
    {
        if (Auth::id() !== $user['id']) {
            return $this->error(403 ,null, 'You do not have authority to change');
        }
        $user->update(['password' => ($request['password'])]);
        return $this->success(200 , new UserRescource($user), 'We change your password successful');

    }

    public function sendResetLink(SendResetLinkRequest $request):JsonResponse
    {
        $status = Password::sendResetLink($request->validated());
        if($status === Password::RESET_LINK_SENT){
            return $this->success(200 ,null, 'We send a link to your email, Please check your email');
        }else{
            return $this->error(403 ,null, 'Filed to send a link to your email , Please try again');
        }
    }

    public function resetPassword(ResetPasswordRequest $request):JsonResponse
    {
        $status = Password::reset($request->all(),
            function (User $user, string $password) {
                $user->update(['password' => $password , 'remember_token' => null]);
                event(new PasswordReset($user));
            }
        );
        if($status === Password::PASSWORD_RESET){
            return $this->success(200 ,null, 'We reset your password successful');
        }else{
            return $this->error(500 ,null, 'Filed to reset your password');
        }
    }
    public function logout():JsonResponse
    {
//        Auth::user()->currentAccessToken()->delete();
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success(200,null, 'Logout successfully');
    }

}
