<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Helpers\JWTHelper;
use Eli\Vacation\Helpers\ResponseCodes;
use Eli\Vacation\Request;
use Eli\Vacation\Response;
use Eli\Vacation\Session;
use Eli\Vacation\User;
use JetBrains\PhpStorm\NoReturn;

class UsersController extends Controller
{

    #[NoReturn] public function register (Request $request)
    {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->save()) {
                $this->response
                    ->setSuccess(true)
                    ->setMessage('Hooray')
                    ->sendResponse();
            }

            $errMsg = '';
            foreach ($user->attributes() as $attribute) {
                if ($user->hasError($attribute)) {
                    $errMsg .= $attribute . ' ' . $user->getFirstError($attribute) . PHP_EOL;
                }
            }
            $this->response->setSuccess(false)->setMessage($errMsg)->sendResponse();
        }

        $this->response
            ->setSuccess(false)
            ->setMessage('Unsupported request method')
            ->sendResponse();
    }

    public function login (Request $request, Response $response)
    {
        if ($request->isPost()) {
            $body = array_change_key_case($request->getBody());
            $username = $body['username'];
            $password = $body['password'];
            $user = User::findOne(['username' => $username]);
            if (!$user || !password_verify($password, $user->password)) {
                $response
                    ->setCode(ResponseCodes::HTTP_TEAPOT)
                    ->setSuccess(false)
                    ->setMessage('Incorrect credentials')
                    ->sendResponse();
            }

            /**
             * @var User $user
             */
            $userId = $user->{User::primaryKey()};
            $token = JWTHelper::generateJwt($userId);
            if (is_array($token)) {
                $response
                    ->setCode(ResponseCodes::HTTP_INTERNAL_SERVER_ERROR)
                    ->setSuccess(false)
                    ->setMessage($token['error_message'])
                    ->sendResponse();
            }

            $user->token = $token;
            $session = new Session();
            $session->set('userId', $userId);
            $session->set('username', $user->username);
            $response
                ->setSuccess(true)
                ->setMessage('')
                ->setData(['user_id' => $userId, 'token' => $user->token])
                ->sendResponse();
        }
    }

}