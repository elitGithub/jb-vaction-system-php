<?php

namespace Eli\Vacation\Models;

use Eli\Vacation\DbModel;
use JetBrains\PhpStorm\ArrayShape;


class Vacation extends DbModel
{
    public string $name = '';
    public string $description = '';
    public string $image = '';
    public string $start_date = '';
    public string $end_date = '';
    public float $price = 0;
    protected array $attributesMap = [
        'startDate' => 'start_date',
        'endDate'   => 'end_date',
    ];

    public static function tableName (): string
    {
        return 'vacations';
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        'name'        => "array",
        'description' => "array",
        'image'       => "array",
        'start_date'  => "array",
        'end_date'    => "array",
        'price'       => "array",
    ])] public function rules (): array
    {
        return [
            'name'        => [static::RULE_REQUIRED],
            'description' => [static::RULE_REQUIRED],
            'image'       => [static::RULE_REQUIRED],
            'start_date'  => [static::RULE_REQUIRED, static::RULE_IS_DATE],
            'end_date'    => [static::RULE_REQUIRED, static::RULE_IS_DATE],
            'price'       => [static::RULE_REQUIRED],
        ];
    }

    public function attributes (): array
    {
        $attributes = ['name', 'description', 'image', 'start_date', 'end_date', 'price'];
        foreach ($attributes as $column) {
            $columns[] = $this->renameAttributes($column);
        }
        return $columns;
    }

    public static function primaryKey (): string
    {
        return 'id';
    }
}