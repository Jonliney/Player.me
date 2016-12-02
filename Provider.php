<?php

namespace SocialiteProviders\Player;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'PLAYER';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [];

    /**
     * {@inherticdoc}.
     */
    protected $scopeSeparator = '';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://player.me/o/auth', $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://player.me/api/oauth/access_token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post(
            'https://player.me/api/graphql', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token
            ],
            'body' => '
                { 
                    "query": "{ me { id username avatar } }"
                }
            ',
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => $user['username'],
            'name' => $user['username'], 'email' => null,
            'avatar' => $user['avatar'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
