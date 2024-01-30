<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function verify($id): JsonResponse|RedirectResponse
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
            return request()->wantsJson() ? new JsonResponse($user->email, 204) : redirect()->away('http://localhost:4200/login');
        }
        return request()->wantsJson() ? new JsonResponse($user->email, 204) : redirect()->away('http://localhost:4200/login');
    }

}
