<?php


namespace App\Matcher;


class FakeDataHelper
{
    public static function propertyTypes()
    {
        return [
            'A',
            'B',
            'C',
            'D',
            'E'
        ];
    }

    public static function randomPropertyType()
    {
        return collect(self::propertyTypes())->random();
    }

    public static function fields()
    {
        return [
            [
                'name' => 'returnScenario',
                'type' => 'number',
            ],
            [
                'name' => 'multipleActual',
                'type' => 'number',
            ],
            [
                'name' => 'priceSquareMeter',
                'type' => 'number',
            ],
            [
                'name' => 'leased',
                'type' => 'boolean',
            ],
            [
                'name' => 'multipleScenario',
                'type' => 'number',
            ],
            [
                'name' => 'area',
                'type' => 'number',
            ],
            [
                'name' => 'returnActual',
                'type' => 'number',
            ],
            [
                'name' => 'price',
                'type' => 'number',
            ],
            [
                'name' => 'yearConstruction',
                'type' => 'number',
            ],
            [
                'name' => 'rooms',
                'type' => 'number',
            ],
            [
                'name' => 'parking',
                'type' => 'boolean',
            ],
        ];
    }

    public static function randomPropertyFields()
    {
        $fields = collect(self::fields());
        $randomFields = $fields->shuffle()->take(rand(0, $fields->count()));
        $propertyFields = [];
        foreach ($randomFields as $field) {
            if ($field['type'] == 'boolean') {
                $propertyFields[$field['name']] = (boolean)rand(0, 1);
                continue;
            }
            if ($field['name'] == 'rooms') {
                $propertyFields[$field['name']] = rand(1, 6);
                continue;
            }
            if ($field['name'] == 'yearConstruction') {
                $propertyFields[$field['name']] = rand(2010, 2020);
                continue;
            }

            $propertyFields[$field['name']] = rand(100, 2000);

        }

        return $propertyFields;
    }

    public static function randomSearchProfileFields()
    {
        $fields = collect(self::fields());
        $randomFields = $fields->shuffle()->take(rand(0, $fields->count()));
        $searchFields = [];

        foreach ($randomFields as $field) {
            if ($field['type'] == 'boolean') {
                $searchFields[$field['name']] =  (boolean)rand(0, 1);
                continue;
            }
            if ($field['name'] == 'rooms') {
                $lower = rand(1, 6);
                $upper = rand($lower, 6);
                $searchFields[$field['name']] =  [$lower, $upper];
                continue;
            }
            if ($field['name'] == 'yearConstruction') {
                $lower = rand(2010, 2020);
                $upper = rand($lower, 2020);
                $searchFields[$field['name']] =  [$lower, $upper];
                continue;
            }
            $lower = rand(100, 2000);
            $upper = rand($lower, 2000);
            $searchFields[$field['name']] =  [$lower, $upper];
        }

        return $searchFields;
    }
}
