<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as OAuthUser;

class AuthenticateOAuthUser
{
    public function authenticate(OAuthUser $oauthUser): ?User
    {
        $user = User::query()
            ->where('email', $oauthUser->getEmail())
            ->whereHas('roles')
            ->first();

        if ($user === null) {
            return null;
        }

        session(['auth_login_method' => 'sso']);

        Auth::login($user);

        return $user;
    }
}
