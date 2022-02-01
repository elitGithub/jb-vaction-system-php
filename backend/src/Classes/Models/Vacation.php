<?php

class Vacation extends Eli\Vacation\Model
{

    public static function tableName (): string
    {
        return 'vacations';
    }

    /**
     * @inheritDoc
     */
    public function rules (): array
    {

        return [];
        /**\
         *         return [
        'firstName'        => [static::RULE_REQUIRED],
        'lastName'         => [static::RULE_REQUIRED],
        'userName'         => [
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
         */
    }
}