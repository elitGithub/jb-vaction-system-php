<?php

namespace Eli\Vacation;

class User extends Model
{
    protected string $table = 'users';


    public function rules (): array
    {
        // TODO: Implement rules() method.
    }
    public static function tableName() {
        return 'users';
    }
}