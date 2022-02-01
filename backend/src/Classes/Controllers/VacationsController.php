<?php

namespace Eli\Vacation\Controllers;

use Eli\Vacation\Helpers\DateTimeHelper;
use Eli\Vacation\Models\Vacation;
use Eli\Vacation\Request;
use JetBrains\PhpStorm\NoReturn;

class VacationsController extends Controller
{

    public function getVacation (Request $request)
    {

    }

    #[NoReturn] public function createVacation (Request $request)
    {
        $vacation = new Vacation();
        $vacation->loadData($request->getBody());
        $vacation->start_date = DateTimeHelper::mutateDateTime($vacation->start_date);
        $vacation->end_date = DateTimeHelper::mutateDateTime($vacation->end_date);
        if ($vacation->validate() && $vacation->save()) {
            $this->response
                ->setSuccess(true)
                ->setMessage('')
                ->setData([])
                ->sendResponse();
        }

        $errMsg = '';
        foreach ($vacation->attributes() as $attribute) {
            if ($vacation->hasError($attribute)) {
                $label = $vacation->getLabel($attribute);
                $errMsg .= $label . ' ' . $vacation->getFirstError($attribute) . ' ';
            }
        }
        $this->response
            ->setSuccess(false)
            ->setMessage($errMsg)
            ->sendResponse();

    }

    public function updateVacation (Request $request)
    {

    }

}