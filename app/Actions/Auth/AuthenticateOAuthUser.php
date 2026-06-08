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

        // $user->forceFill([
        //     'oauth' => [
        //         'provider' => 'sso',
        //         'provider_id' => $oauthUser->getId(),
        //         'access_token' => $oauthUser->token,
        //         'refresh_token' => $oauthUser->refreshToken,
        //         'expires_at' => $oauthUser->expiresIn
        //           ? now()->addSeconds($oauthUser->expiresIn)->toIso8601String()
        //           : null,
        //     ],
        // ])->save();

        Auth::login($user);

        return $user;
    }
}
