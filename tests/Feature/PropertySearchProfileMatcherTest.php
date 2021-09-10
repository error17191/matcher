<?php

namespace Tests\Feature;

use App\Matcher\PropertySearchProfileMatcher;
use App\Models\Property;
use App\Models\SearchProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertySearchProfileMatcherTest extends TestCase
{
    public function test_it_can_detect_matching()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'parking' => true,
                'price' => 100000,
                'yearConstruction' => 2010,
                'rooms' => 3,
                'area' => 320
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2,4],
                'price' => [90000, 120000],
                'area' => [400,500],
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price']);
    }
}
