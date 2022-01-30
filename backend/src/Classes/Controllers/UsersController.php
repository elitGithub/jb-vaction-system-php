<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Request;
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
                $this->response->setSuccess(true)->setMessage('Hooray')->sendResponse();
            }

            $errMsg = '';
            foreach ($user->attributes() as $attribute) {
                if ($user->hasError($attribute)) {
                    $errMsg .= $attribute . ' ' . $user->getFirstError($attribute) . PHP_EOL;
                }
            }
            $this->response->setSuccess(false)->setMessage($errMsg)->sendResponse();
        }

        $this->response->setSuccess(false)->setMessage('Unsupported request method')->sendResponse();
    }

    public function login () {}

}