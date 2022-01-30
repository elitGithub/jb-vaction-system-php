<?php

namespace Eli\Vacation;

use Eli\Vacation\Helpers\FilesHandler;

class LogWriter
{

    public function error (string $message, array $context = []) {
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        $str = '';
        foreach ($context as $key => $value) {
            $str .= $key . ' ' . $value . ' ';
        }

        $dirName = dirname(__FILE__, 3) . DS . 'logs';
        $fileName = 'errorLog.txt';
        $filePath = $dirName . DS . $fileName;
        FilesHandler::makeFile($dirName, $filePath);
        file_put_contents($filePath, $message . ' ' . $str, FILE_APPEND);
    }

    public function critical ($message, $context) {
        $str = '';
        foreach ($context as $key => $value) {
            $str .= $key . ' ' . $value . ' ';
        }
        $dirName = dirname(__FILE__, 3) . DS . 'logs';
        $fileName = 'criticalLog.txt';
        $filePath = $dirName . DS . $fileName;
        FilesHandler::makeFile($dirName, $filePath);
        file_put_contents($filePath, $message . ' ' . $str, FILE_APPEND);
    }

}