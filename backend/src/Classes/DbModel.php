<?php

namespace Eli\Vacation;

use Throwable;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;

    abstract public function attributes(): array;

    abstract public static function primaryKey(): string;

    public function save(): bool
    {
        try {
            $tableName = static::tableName();
            $columns = $this->attributes();
            $placeholders =  Helpers\Questionify::generateQuestionMarks($columns);
            $query = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES ($placeholders);";
            $values = [];
            foreach ($columns as $attribute) {
                if (method_exists($this, 'renameAttributes')) {
                    $attribute = $this->renameAttributes($attribute);
                }
                $values[] = $this->{$attribute};
            }
            $result = static::$db->preparedQuery($query, $values);
            if ($result && $result->rowCount() > 0) {
                return true;
            }

            return false;
        } catch (Throwable $e) {
            if (method_exists($this, 'logErrors')) {
                // TODO: add log!
                $this->logErrors($e->getMessage(), $e->getTraceAsString());
            }
            return false;
        }
    }
}