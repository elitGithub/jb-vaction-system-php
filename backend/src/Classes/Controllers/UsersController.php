<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Helpers\AuthHelper;
use Eli\Vacation\Helpers\JWTHelper;
use Eli\Vacation\Helpers\ResponseCodes;
use Eli\Vacation\Request;
use Eli\Vacation\User;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;

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
                    ->setMessage('')
                    ->sendResponse();
            }

            $errMsg = '';
            foreach ($user->attributes() as $attribute) {
                if ($user->hasError($attribute)) {
                    $label = $user->getLabel($attribute);
                    $errMsg .= $label . ' ' . $user->getFirstError($attribute) . ' ';
                }
            }
            $this->response->setSuccess(false)->setMessage($errMsg)->sendResponse();
        }

        $this->response
            ->setSuccess(false)
            ->setMessage('Unsupported request method')
            ->sendResponse();
    }

    #[NoReturn] public function validateToken(Request $request) {
        $headers = array_change_key_case($request->getHeaders());
        if (empty($headers['authorization'])) {
            $this->response
                ->setCode(ResponseCodes::HTTP_UNAUTHORIZED)
                ->setSuccess(false)
                ->setMessage('Missing authorization')
                ->sendResponse();
        }
        $authToken = trim(str_ireplace('bearer', '', $headers['authorization']));
        if (!JWTHelper::validate($authToken)) {
            $this->response
                ->setCode(ResponseCodes::HTTP_UNAUTHORIZED)
                ->setSuccess(false)
                ->setMessage('Invalid token')
                ->sendResponse();
        }

        $parsedToken = JWTHelper::parseToken($authToken);

        AuthHelper::refreshToken(User::findOne(['id' => $parsedToken['user_id']]));
        $this->response
            ->setSuccess(true)
            ->setMessage('')
            ->setData($this->retrieveUserDataFromSession())
            ->sendResponse();
    }

    #[Pure] #[ArrayShape(['user_id'   => "false|mixed",
                          'firstName' => "false|mixed",
                          'lastName'  => "false|mixed",
                          'active'    => "false|mixed",
                          'roles'     => "false|mixed",
                          'isAdmin'   => "false|mixed",
                          'token'     => "false|mixed"
    ])] private function retrieveUserDataFromSession(): array
    {
        return [
            'user_id'   => $this->session->get('userId'),
            'firstName' => $this->session->get('first_name'),
            'lastName'  => $this->session->get('last_name'),
            'active'    => $this->session->get('status'),
            'roles'     => $this->session->get('role'),
            'isAdmin'   => $this->session->get('isAdmin'),
            'token'     => $this->session->get('token'),
        ];
    }

    public function login (Request $request)
    {
        if ($request->isPost()) {
            $body = array_change_key_case($request->getBody());
            $user = AuthHelper::login($body);

            if (!$user) {
                $this->response
                    ->setCode(ResponseCodes::HTTP_TEAPOT)
                    ->setSuccess(false)
                    ->setMessage('Incorrect credentials')
                    ->sendResponse();
            }

            $this->response
                ->setSuccess(true)
                ->setMessage('')
                ->setData($this->retrieveUserDataFromSession())
                ->sendResponse();
        }
    }

}