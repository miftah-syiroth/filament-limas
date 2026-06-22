<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class LogAuthenticationActivity
{
    public function handleLogin(Login $event): void
    {
        if (! config('activitylog.enabled')) {
            return;
        }

        $method = session()->pull('auth_login_method', 'password');

        $this->log(
            user: $event->user,
            event: 'login',
            description: __('activitylog.auth.login', ['method' => __("activitylog.auth.methods.{$method}")]),
            properties: [
                'guard' => $event->guard,
                'method' => $method,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'remember' => $event->remember,
            ],
        );
    }

    public function handleLogout(Logout $event): void
    {
        if (! config('activitylog.enabled') || ! $event->user instanceof Authenticatable) {
            return;
        }

        $this->log(
            user: $event->user,
            event: 'logout',
            description: __('activitylog.auth.logout'),
            properties: [
                'guard' => $event->guard,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    private function log(Authenticatable $user, string $event, string $description, array $properties): void
    {
        if (! $user instanceof Model) {
            return;
        }

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->event($event)
            ->withProperties($properties)
            ->log($description);
    }
}
