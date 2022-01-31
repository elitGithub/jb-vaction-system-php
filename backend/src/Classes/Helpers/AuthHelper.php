<?php

namespace Eli\Vacation\Helpers;

use Eli\Vacation\LogWriter;
use Eli\Vacation\Session;
use Eli\Vacation\User;

class AuthHelper
{

    public static function login (array $requestBody): bool
    {
        $username = $requestBody['username'];
        $password = $requestBody['password'];
        $user = User::findOne(['username' => $username]);
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        /**
         * @var User $user
         */
        $userId = $user->{User::primaryKey()};
        $token = JWTHelper::generateJwt($userId);

        if (is_array($token)) {
            LogWriter::getLogger()->error($token['error_message']);
            return false;
        }

        static::setSessionData($user, $token);
        return true;
    }

    public static function refreshToken(User $user) {
        $userId = $user->{User::primaryKey()};
        $token = JWTHelper::generateJwt($userId);
        static::setSessionData($user, $token);
    }

    private static function setSessionData (User $user, string $token)
    {
        $userId = $user->{User::primaryKey()};
        $user->token = $token;
        $session = new Session();
        $session->set('userId', $userId);
        $session->set('username', $user->username);
        $session->set('first_name', $user->first_name);
        $session->set('last_name', $user->last_name);
        $session->set('active', $user->status);
        // TODO: this will later change to dynamic to control basic permissions.
        $session->set('role', 'admin');
        $session->set('isAdmin', true);
        $session->set('token', $token);
    }

}