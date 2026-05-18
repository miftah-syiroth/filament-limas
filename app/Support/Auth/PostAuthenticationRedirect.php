<?php

namespace App\Support\Auth;

use App\Models\User;

class PostAuthenticationRedirect
{
    public static function pathFor(User $user): string
    {
        // if ($user->hasRole('staff')) {
        //     return '/app';
        // }

        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return '/admin';
        }

        return '/admin';
    }
}
