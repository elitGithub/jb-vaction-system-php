<?php

namespace Eli\Vacation;

use Eli\Vacation\Helpers\JWTHelper;

class Session
{
    protected const FLASH_KEY = 'flash_messages';
    protected const TOKEN_KEY = 'token';
    private Request $request;
    private Response $response;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->request = new Request();
        $this->response = new Response();
        $flashMessages = $_SESSION[static::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[static::TOKEN_KEY] = '';
        $_SESSION[static::FLASH_KEY] = $flashMessages;
    }

    public function validateSession(): bool
    {
        $headers = $this->request->getHeaders();
        $request = $this->request->getBody();
        $authToken = null;
        if (isset($headers['Authorization'])) {
            $authToken = str_replace('Bearer ', '', $headers['Authorization']) ?? null;
        }

        if (isset($request['token'])) {
            $authToken = str_replace('Bearer ', '', $request['token']) ?? null;
        }

        if (!$authToken) {
            return false;
        }

        if (JWTHelper::validate($authToken)) {
            $payload = JWTHelper::parseToken($authToken);
            $user = User::findOne(['id' => $payload['user_id']]);
            $userData = [
                'user'     => $payload['user_id'] . '#' . $user->username,
                'userid'   => $payload['user_id'],
                'role'     => $user->getRoleId(),
                'username' => $user->username,
                'token'    => $authToken,
            ];
            $this->setArray($userData);
            return true;
        }
        return false;

    }

    public function setFlash($key, $message)
    {
        $_SESSION[static::FLASH_KEY][$key] = [
            'value'  => $message,
            'remove' => false,
        ];
    }

    public function setArray(array $userValues)
    {
        foreach ($userValues as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function getFlash($key)
    {
        return $_SESSION[static::FLASH_KEY][$key]['value'] ?? false;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[static::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove'] === true) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[static::FLASH_KEY] = $flashMessages;
    }

}