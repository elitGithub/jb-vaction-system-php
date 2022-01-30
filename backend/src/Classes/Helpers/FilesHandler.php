<?php

namespace Eli\Vacation\Helpers;

class FilesHandler
{

    public static function makeFile ($dirName, $filePath)
    {
        if (!is_dir($dirName)) {
            mkdir($dirName);
        }
        if (!is_file($filePath)) {
            touch($filePath);
        }
    }

}