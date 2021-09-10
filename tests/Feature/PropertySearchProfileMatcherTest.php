<?php

namespace Tests\Feature;

use App\Matcher\PropertySearchProfileMatcher;
use App\Models\Property;
use App\Models\SearchProfile;
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
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, 500],
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price']);
        $this->assertEquals($matcher->looseMatchingFields(), ['area']);
    }

    public function test_it_can_detect_mismatching()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'parking' => false,
                'price' => 100000,
                'yearConstruction' => 2010,
                'rooms' => 5,
                'area' => 320
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, 500],
                'parking' => true,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertFalse($matcher->areMatching());
    }


    public function test_it_doesnt_match_missing_property_fields()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'parking' => false,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 320
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, 500],
                'parking' => true,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertFalse($matcher->areMatching());
    }

    public function test_it_doesnt_match_null_property_fields()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'parking' => false,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 320,
                'rooms' => null
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, 500],
                'parking' => true,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertFalse($matcher->areMatching());
    }

    public function test_it_can_match_null_search_profile_fields()
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
                'area' => 320,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, 500],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());

        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price', 'parking']);
        $this->assertEquals($matcher->looseMatchingFields(), ['area']);
    }

    public function test_it_can_match_range_upper_null_search_profile_field()
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
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 4],
                'price' => [90000, 120000],
                'area' => [400, null],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price', 'area', 'parking']);
        $this->assertEquals($matcher->looseMatchingFields(), []);

    }

    public function test_it_can_match_range_lower_null_search_profile_field()
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
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 3],
                'price' => [null, 120000],
                'area' => [400, null],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price', 'area', 'parking']);
        $this->assertEquals($matcher->looseMatchingFields(), []);

    }

    public function test_it_can_match_null_in_both_fields()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'parking' => null,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 3],
                'price' => [null, 120000],
                'area' => [400, null],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price', 'area', 'parking']);
        $this->assertEquals($matcher->looseMatchingFields(), []);

    }

    public function test_it_can_match_null_search_field_with_missing_property_field()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 3],
                'price' => [null, 120000],
                'area' => [400, null],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
        $this->assertEquals($matcher->strictMatchingFields(), ['rooms', 'price', 'area', 'parking']);
        $this->assertEquals($matcher->looseMatchingFields(), []);

    }

    public function test_it_doesnt_match_different_property_types()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeB',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2, 3],
                'price' => [null, 120000],
                'area' => [400, null],
                'parking' => null,
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertFalse($matcher->areMatching());
    }

    public function test_it_can_match_empty_search_fields()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
    }

    public function test_it_can_match_all_null_search_fields()
    {
        $property = Property::make([
            'name' => 'My Property',
            'propertyType' => 'typeA',
            'address' => 'someAddress',
            'fields' => [
                'priceSquareMeter' => 500,
                'price' => 100000,
                'yearConstruction' => 2010,
                'area' => 900,
                'rooms' => 3
            ],
        ]);

        $searchProfile = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'parking' => null
            ]
        ]);

        $matcher = new PropertySearchProfileMatcher($property, $searchProfile);
        $this->assertTrue($matcher->areMatching());
    }
}
