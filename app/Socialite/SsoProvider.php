<?php

namespace App\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SsoProvider extends AbstractProvider implements ProviderInterface
{
    protected string $baseUrl;

    protected string $userAgent;

    protected $scopeSeparator = ' ';

    public function setBaseUrl(string $baseUrl): static
    {
        $this->baseUrl = rtrim($baseUrl, '/');

        return $this;
    }

    public function setUserAgent(string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function resolveUserFromCallback(): User
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $tokenResponse = $this->getAccessTokenResponse($this->getCode());

        return $this->fetchProfile(
            Arr::get($tokenResponse, 'access_token'),
            $tokenResponse,
        );
    }

    public function fetchProfile(string $accessToken, ?array $tokenResponse = null): User
    {
        $profile = $this->getUserByToken($accessToken);

        if ($tokenResponse !== null) {
            return $this->userInstance($tokenResponse, $profile);
        }

        return $this->mapUserToObject($profile)->setToken($accessToken);
    }

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase($this->baseUrl.'/oauth/authorize', $state);
    }

    protected function getTokenUrl(): string
    {
        return $this->baseUrl.'/oauth/token';
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get(
            $this->baseUrl.'/api/v1/profile',
            [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token,
                    'Cache-Control' => 'no-cache',
                    'User-Agent' => $this->userAgent,
                ],
            ],
        );

        $user = json_decode($response->getBody()->getContents(), true);

        return Arr::get($user, 'data', $user) ?? [];
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'email' => Arr::get($user, 'email'),
            'nickname' => Arr::get($user, 'username'),
            'name' => Arr::get($user, 'full_name', Arr::get($user, 'name')),
            'avatar' => Arr::get($user, 'profile_photo_url', Arr::get($user, 'avatar')),
        ]);
    }

    protected function getTokenHeaders($code): array
    {
        return [
            'Accept' => 'application/json',
            'User-Agent' => $this->userAgent,
        ];
    }
}
