<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AuthenticateOAuthUser;
use App\Http\Controllers\Controller;
use App\Socialite\SsoProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class SsoController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('sso')->redirect();
    }

    public function callback(Request $request, AuthenticateOAuthUser $authenticateOAuthUser): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => __('auth.oauth_denied')]);
        }

        try {
            /** @var SsoProvider $provider */
            $provider = Socialite::driver('sso');
            $oauthUser = $provider->resolveUserFromCallback();
        } catch (InvalidStateException) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => __('auth.oauth_invalid_state')]);
        } catch (Throwable $exception) {
            Log::warning('SSO callback failed', [
                'message' => $exception->getMessage(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors(['email' => __('auth.oauth_failed')]);
        }

        if (blank($oauthUser->getEmail())) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => __('auth.oauth_missing_email')]);
        }

        $user = $authenticateOAuthUser->authenticate($oauthUser);

        if ($user === null) {
            throw new HttpException(403, __('auth.oauth_unauthorized'));
        }

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }
}
