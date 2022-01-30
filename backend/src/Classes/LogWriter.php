<?php

namespace Eli\Vacation;

class LogWriter
{

    public function error (string $message, array $context = []) {
        $str = '';
        foreach ($context as $key => $value) {
            $str .= $key . ' ' . $value . ' ';
        }
        file_put_contents('../../logs/errorLog.txt', $message . ' ' . $str, FILE_APPEND);
    }

    public function critical ($message, $context) {
        $str = '';
        foreach ($context as $key => $value) {
            $str .= $key . ' ' . $value . ' ';
        }
        file_put_contents('../../logs/criticalLog.txt', $message . ' ' . $str, FILE_APPEND);
    }

}