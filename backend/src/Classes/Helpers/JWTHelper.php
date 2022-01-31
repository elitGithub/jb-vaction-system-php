<?php

namespace Eli\Vacation\Helpers;

use ReallySimpleJWT\Token;
use Throwable;

class JWTHelper
{
    public static ?string $appSecret;

    public static function initSecret (): ?string
    {
        if (isset(static::$appSecret)) {
            return static::$appSecret;
        }

        static::$appSecret = getenv('SECRET_KEY');
        return static::$appSecret;
    }

    public static function parseToken ($token): array
    {
        $token = str_replace('Bearer ', '', $token);
        return Token::parser($token, static::initSecret())->parse()->getPayload();
    }

    public static function validate ($token): bool
    {
        return Token::validate($token, static::initSecret()) && Token::validateExpiration($token, static::initSecret());
    }

    public static function getToken ()
    {
        return $_SESSION['token'];
    }

    public static function generateJwt ($userId): bool | string | array
    {
        try {
            $expiration = time() + 86400;
            $issuer = 'localhost';
            return Token::create($userId, getenv('SECRET_KEY'), $expiration, $issuer);
        } catch (Throwable $e) {
            return [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage()
            ];
        }
    }

}