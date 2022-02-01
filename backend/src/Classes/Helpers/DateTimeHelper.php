<?php

namespace Eli\Vacation\Helpers;

use DateTimeImmutable;
use Throwable;

class DateTimeHelper
{

    /**
     * @param $string
     *
     * @return bool
     */
    public static function isValidDateTime($string): bool
    {
        if (empty($string)) {
            return false;
        }

        try {
            new DateTimeImmutable($string);
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * @param $dateTime
     *
     * @return mixed
     */
    public static function mutateDateTime ($dateTime): mixed
    {
        if (!static::isValidDateTime($dateTime)) {
            return $dateTime;
        }
        try {
            $immutable = new DateTimeImmutable($dateTime);
            return $immutable->format("Y-m-d H:i:s");
        } catch (Throwable) {
            return $dateTime;
        }
    }

}