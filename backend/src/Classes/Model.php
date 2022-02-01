<?php

namespace Eli\Vacation;

use Eli\Vacation\Helpers\DateTimeHelper;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

abstract class Model
{

    protected static ?Database $db = null;

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_IS_DATE = 'is_valid_date_time';

    public array $errors = [];

    public function __construct ()
    {
        static::$db = PDOImplementation::getInstance();
    }

    public function loadData (array $data)
    {
        foreach ($data as $key => $value) {
            if (method_exists($this, 'renameAttributes')) {
                $key = $this->renameAttributes($key);
            }

            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return array
     */
    abstract public function rules (): array;

    public function labels (): array
    {
        return [];
    }

    #[Pure] public function getLabel ($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * @return bool
     */
    public function validate (): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                if ($ruleName === static::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, static::RULE_REQUIRED, $rule);
                }

                if ($ruleName === static::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, static::RULE_EMAIL, $rule);
                }

                if ($ruleName === static::RULE_MIN && (strlen($value) < $rule[static::RULE_MIN])) {
                    $this->addErrorForRule($attribute, static::RULE_MIN, $rule);
                }

                if ($ruleName === static::RULE_MAX && (strlen($value) > $rule[static::RULE_MAX])) {
                    $this->addErrorForRule($attribute, static::RULE_MAX, $rule);
                }

                if ($ruleName === static::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, static::RULE_MATCH, $rule);
                }

                if ($ruleName === static::RULE_IS_DATE && !DateTimeHelper::isValidDateTime($value)) {
                    $this->addErrorForRule($attribute, static::RULE_IS_DATE, $rule);
                }

                if ($ruleName === static::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;

                    if ($this->uniqueAttribute($className::tableName(), $uniqueAttr, $value)) {
                        $this->addErrorForRule(
                            $attribute,
                            static::RULE_UNIQUE,
                            ['field' => $this->getLabel($attribute)]
                        );
                    }
                }
            }
        }

        return empty($this->errors);
    }

    protected function uniqueAttribute ($tableName, $uniqueAttrName, $uniqueAttrValue): bool
    {
        if (!(static::$db instanceof Database)) {
            static::$db = PDOImplementation::getInstance();
        }
        $result = static::$db->preparedQuery("SELECT * FROM $tableName WHERE $uniqueAttrName = ?", [$uniqueAttrValue]);
        if (!$result) {
            return false;
        }

        return $result->rowCount() > 0;
    }

    /**
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array|string  $params
     */
    public function addErrorForRule (string $attribute, string $rule, array | string $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }
        $this->addError($attribute, $message);
    }


    public function addError (string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    /**
     * @return string[]
     */
    #[ArrayShape([
        self::RULE_REQUIRED => "string",
        self::RULE_EMAIL    => "string",
        self::RULE_MIN      => "string",
        self::RULE_MAX      => "string",
        self::RULE_MATCH    => "string",
        self::RULE_UNIQUE   => "string",
        self::RULE_IS_DATE  => "string",
    ])] public function errorMessages (): array
    {
        return [
            static::RULE_REQUIRED => 'This field is required.',
            static::RULE_EMAIL    => 'This field must be a valid email address.',
            static::RULE_MIN      => 'This field must be at least {min} characters long.',
            static::RULE_MAX      => 'This field must be at most {max} characters long.',
            static::RULE_MATCH    => 'This field must be the same as {match}.',
            static::RULE_UNIQUE   => 'Record with this {field} already exists.',
            static::RULE_IS_DATE  => 'This field must be a valid date time.',
        ];
    }

    /**
     * @param $attribute
     *
     * @return mixed
     */
    public function hasError ($attribute): mixed
    {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * @param $attribute
     *
     * @return mixed
     */
    public function getFirstError ($attribute): mixed
    {
        return $this->errors[$attribute][0] ?? '';
    }
}