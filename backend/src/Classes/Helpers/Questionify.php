<?php

namespace Eli\Vacation\Helpers;

class Questionify
{

    public static function generateQuestionMarks ($items_list): string
    {
        $items_list = is_array($items_list) ? $items_list : explode(",", $items_list);
        return implode(",", array_map(fn($attr) => '?', $items_list));
    }


}