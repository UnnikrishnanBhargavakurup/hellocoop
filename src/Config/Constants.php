<?php

namespace HelloCoop\Config;

class Constants
{
    public static string $PRODUCTION_WALLET = 'https://wallet.hello.coop';
    public static string $DEFAULT_PATH = '/authorize';
    public static string $HELLO_API_ROUTE =  '/api/hellocoop';
    /** @var array<string> */
    public static array $DEFAULT_SCOPE = ['openid', 'name', 'email', 'picture'];
    public static string $DEFAULT_RESPONSE_TYPE = 'code';
    public static string $DEFAULT_RESPONSE_MODE = 'query';

    /** @var array<string> */
    public static array $VALID_IDENTITY_STRING_CLAIMS = [
        'name', 'nickname', 'preferred_username', 'given_name', 'family_name',
        'email', 'phone', 'picture', 'ethereum',
    ];

    /**
     * @var array<string>
     */
    public static array $VALID_IDENTITY_ACCOUNT_CLAIMS = [
        'discord', 'twitter', 'github', 'gitlab'
    ];

    public static string $ORG_CLAIM = 'org';

    /**
     * @return array<string>
     */
    public static function getValidIdentityClaims(): array
    {
        return array_merge(
            self::$VALID_IDENTITY_STRING_CLAIMS,
            self::$VALID_IDENTITY_ACCOUNT_CLAIMS,
            ['org', 'email_verified', 'phone_verified']
        );
    }

    /**
     * @return array<string>
     */
    public static function getValidScopes(): array
    {
        return array_merge(
            self::$VALID_IDENTITY_STRING_CLAIMS,
            self::$VALID_IDENTITY_ACCOUNT_CLAIMS,
            ['profile', 'openid', 'profile_update']
        );
    }

    /** @var array<string> */
    public static array $VALID_RESPONSE_TYPE = ['id_token', 'code'];
    /** @var array<string> */
    public static array $VALID_RESPONSE_MODE = ['fragment', 'query', 'form_post'];
    /** @var array<string> */
    public static array $VALID_PROVIDER_HINT = [
        'apple', 'discord', 'facebook', 'github', 'gitlab', 'google',
        'twitch', 'twitter', 'tumblr', 'mastodon', 'microsoft', 'line',
        'wordpress', 'yahoo', 'phone', 'ethereum', 'qrcode',
        'apple--', 'microsoft--'
    ];
}
