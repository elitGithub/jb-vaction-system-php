<?php

namespace Eli\Vacation\Exceptions;

use Exception;
use Eli\Vacation\Helpers\ResponseCodes;

class NotFoundException extends Exception
{
    protected $code = ResponseCodes::HTTP_NOT_FOUND;
    protected $message = 'Page not found';

}