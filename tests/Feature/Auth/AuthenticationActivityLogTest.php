<?php

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Socialite\SsoProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

test('password login is recorded in activity log', function () {
    $role = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertRedirect('/admin');

    $log = ActivityLog::query()
        ->where('event', 'login')
        ->where('causer_id', $user->id)
        ->latest()
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->log_name)->toBe('auth')
        ->and($log->properties->get('method'))->toBe('password');

    expect(ActivityLog::query()->where('event', 'login')->where('causer_id', $user->id)->count())->toBe(1);
});

test('logout is recorded in activity log', function () {
    $role = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));

    $log = ActivityLog::query()
        ->where('event', 'logout')
        ->where('causer_id', $user->id)
        ->latest()
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->log_name)->toBe('auth');
});

test('sso login is recorded in activity log', function () {
    $role = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);

    $user = User::factory()->create([
        'email' => 'admin@siris.uhb.ac.id',
    ]);
    $user->assignRole($role);

    config([
        'services.sso.url' => 'https://my.uhb.ac.id',
        'services.sso.client_id' => 'test-client',
        'services.sso.client_secret' => 'test-secret',
        'services.sso.redirect' => 'http://localhost/oauth/callback',
        'services.sso.user_agent' => 'test-agent',
    ]);

    $socialite = app(SocialiteFactory::class);

    $socialite->extend('sso', function () use ($socialite) {
        $config = config('services.sso');
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'access_token' => 'access-token',
                'refresh_token' => 'refresh-token',
                'expires_in' => 3600,
            ])),
            new Response(200, [], json_encode([
                'id' => 'sso-123',
                'email' => 'admin@siris.uhb.ac.id',
                'username' => 'admin',
                'full_name' => 'Admin',
            ])),
        ]);

        /** @var SsoProvider $provider */
        $provider = $socialite->buildProvider(SsoProvider::class, $config);

        return $provider
            ->setBaseUrl($config['url'])
            ->setUserAgent($config['user_agent'])
            ->setHttpClient(new Client(['handler' => HandlerStack::create($mock)]));
    });

    $this
        ->withSession(['state' => 'test-state'])
        ->get(route('oauth.callback', [
            'code' => 'auth-code',
            'state' => 'test-state',
        ]))
        ->assertRedirect('/admin');

    $log = ActivityLog::query()
        ->where('event', 'login')
        ->where('causer_id', $user->id)
        ->latest()
        ->first();

    expect($log)->not->toBeNull()
        ->and($log->log_name)->toBe('auth')
        ->and($log->properties->get('method'))->toBe('sso');
});
