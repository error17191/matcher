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
                $propertyFields[$field['name']] = self::randomBoolean();
                continue;
            }
            if ($field['name'] == 'rooms') {
                $propertyFields[$field['name']] = self::randomNumber(1,6);
                continue;
            }
            if ($field['name'] == 'yearConstruction') {
                $propertyFields[$field['name']] = self::randomNumber(2010, 2020);
                continue;
            }

            $propertyFields[$field['name']] = self::randomNumber(100, 2000);

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
                $searchFields[$field['name']] =  self::randomBoolean();
                continue;
            }
            if ($field['name'] == 'rooms') {
                $searchFields[$field['name']] =  self::randomRange(1, 6);
                continue;
            }
            if ($field['name'] == 'yearConstruction') {
                $searchFields[$field['name']] =  self::randomRange(2010,2020);
                continue;
            }
            $searchFields[$field['name']] =  self::randomRange(100, 2000);
        }

        return $searchFields;
    }

    protected static function randomBoolean()
    {
        if(self::useNullHere()){
            return null;
        }

        return (bool) rand(0,1);
    }

    protected static function randomNumber($lower, $upper)
    {
        return self::useNullHere() ? null : rand($lower, $upper);
    }

    protected static function randomRange($minLower, $maxUpper)
    {
        $lower = self::useNullHere() ? null : rand($minLower, $maxUpper);

        $upper = self::useNullHere() ? null : rand(is_null($lower) ? $minLower : $lower, $maxUpper);

        return [$lower, $upper];
    }

    private static function useNullHere()
    {
        return rand(1,10) == 10;
    }
}
