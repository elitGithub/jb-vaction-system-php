<?php

namespace Eli\Vacation;

use JetBrains\PhpStorm\ArrayShape;

class User extends DbModel
{
    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_DELETED = 2;
    public string $first_name = '';
    public string $last_name = '';
    public string $username = '';
    public string $password = '';
    public int $status = self::STATUS_INACTIVE;
    public string $confirm_password = '';


    public function attributes (): array
    {
        $attributes = ['firstName', 'lastName', 'userName', 'password', 'status'];
        $columns = [];
        foreach ($attributes as $column) {
            $columns[] = $this->renameAttributes($column);
        }
        return $columns;
    }

    #[ArrayShape([
        'first_name'        => "array",
        'last_name'         => "array",
        'username'         => "array",
        'password'         => "array",
        'confirm_password' => "array",
    ])] public function rules (): array
    {
        return [
            'first_name'        => [static::RULE_REQUIRED],
            'last_name'         => [static::RULE_REQUIRED],
            'username'         => [
                static::RULE_REQUIRED,
                static::RULE_EMAIL,
                [static::RULE_UNIQUE, 'class' => static::class],
            ],
            'password'         => [
                static::RULE_REQUIRED,
                [static::RULE_MIN, static::RULE_MIN => 8],
                [static::RULE_MAX, static::RULE_MAX => 24],
            ],
            'confirm_password' => [static::RULE_REQUIRED, [static::RULE_MATCH, static::RULE_MATCH => 'password']],
        ];
    }

    public static function tableName (): string
    {
        return 'users';
    }

    #[ArrayShape([
        'first_name'       => "string",
        'last_name'        => "string",
        'email'            => "string",
        'password'         => "string",
        'confirm_password' => "string",
    ])] public function labels (): array
    {
        return [
            'first_name'       => 'First Name',
            'last_name'        => 'Last Name',
            'email'            => 'Email',
            'password'         => 'Password',
            'confirm_password' => 'Confirm Password',
        ];
    }

    public static function primaryKey (): string
    {
        return 'id';
    }

    public function getDisplayName (): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function save (): bool
    {
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->status = static::STATUS_ACTIVE;

        return parent::save();
    }

    public function renameAttributes ($attribute): string
    {
        $map = [
            'firstName' => 'first_name',
            'lastName'  => 'last_name',
            'userName'  => 'username',
            'password'  => 'password',
        ];

        if (isset($map[$attribute])) {
            return $map[$attribute];
        }

        return $attribute;


    }

}