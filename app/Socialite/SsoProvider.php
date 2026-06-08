<?php

namespace App\Socialite;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
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
            $this->baseUrl.'/api/v1/user',
            $this->getRequestOptions($token),
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'nickname' => Arr::get($user, 'username', Arr::get($user, 'email')),
            'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
            'avatar' => Arr::get($user, 'avatar'),
        ]);
    }

    protected function getRequestOptions($token): array
    {
        return [
            RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
                'User-Agent' => $this->userAgent,
            ],
        ];
    }

    protected function getTokenHeaders($code): array
    {
        return [
            'Accept' => 'application/json',
            'User-Agent' => $this->userAgent,
        ];
    }
}
