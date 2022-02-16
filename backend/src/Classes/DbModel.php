<?php

namespace Eli\Vacation;

use Throwable;

abstract class DbModel extends Model
{
    protected array $attributesMap;
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
                $values[] = $this->{$attribute};
            }
            $result = static::$db->preparedQuery($query, $values);
            if ($result && $result->rowCount() > 0) {
                return true;
            }

            return false;
        } catch (Throwable $e) {
            if (method_exists($this, 'logErrors')) {
                $this->logErrors($e->getMessage(), $e->getTraceAsString());
            }
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function findOne (array $where = [])
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sqlWhere = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $db = PDOImplementation::getInstance();
        $result = $db->preparedQuery("SELECT * FROM $tableName WHERE $sqlWhere", $where);
        if ($result && $result->rowCount() > 0) {
            return $result->fetchObject(static::class);
        }

        return null;
    }

    public function renameAttributes ($attribute): string
    {

        if (isset($this->attributesMap[$attribute])) {
            return $this->attributesMap[$attribute];
        }

        return $attribute;


    }
}