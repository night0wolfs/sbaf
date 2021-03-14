<?php

namespace App\Providers\EveProviders;


use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = [];
    protected $scopeSeparator = ' ';

    /**
     * @inheritDoc
     */
    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase('https://login.eveonline.com/v2/oauth/authorize/', $state);
    }

    /**
     * @inheritDoc
     */
    protected function getTokenUrl(): string
    {
        return 'https://login.eveonline.com/v2/oauth/token';
    }

    protected function getTokenVerifyUrl(): string
    {
        return 'https://login.eveonline.com/oauth/verify';
    }

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }

        $response = $this->getAccessTokenResponse($this->getCode());
        $this->credentialsResponseBody = $response;

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = $this->parseAccessToken($response)
        ));

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->credentialsResponseBody);
        }

        return $user
//            ->setToken($token)
//            ->setRefreshToken($this->parseRefreshToken($response))
//            ->setExpiresIn($this->parseExpiresIn($response))
            ;
    }

    /**
     * @inheritDoc
     */
    protected function getUserByToken($token)
    {
        // TODO: Implement getUserByToken() method.
        $response = Http::withToken($token)->get($this->getTokenVerifyUrl());
        return json_decode($response, true);
    }

    /**
     * @inheritDoc
     */
    protected function mapUserToObject(array $user): \Laravel\Socialite\Two\User
    {
        // TODO: Implement mapUserToObject() method.
        return (new User)->setRaw($user)->map([
        ]);
    }
}
