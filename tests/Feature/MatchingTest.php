<?php

namespace Tests\Feature;

use App\Matcher\SearchProfileCollection;
use App\Models\Property;
use App\Models\SearchProfile;
use Tests\TestCase;

class MatchingTest extends TestCase
{

    public function test_core_functionality()
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

        $searchProfile1 = SearchProfile::make([
            'id' => 1,
            'name' => 'Profile 1',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2,4],
                'price' => [90000, 120000],
                'area' => [400,500]
            ]
        ]);

        $searchProfile2 = SearchProfile::make([
            'id' => 2,
            'name' => 'Profile 2',
            'propertyType' =>  'typeB',
            'searchFields' => [
                'price' => [50000,110000]
            ],
        ]);

        $searchProfile3 = SearchProfile::make([
            'id' => 3,
            'name' => 'Profile 3',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [4,5],
                'price' => [100000, 110000]
            ]
        ]);

        $searchProfile4 = SearchProfile::make([
            'id' => 4,
            'name' => 'Profile 4',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2,null],
                'price' => [null, 120000],
            ]
        ]);

        $searchProfile5 = SearchProfile::make([
            'id' => 5,
            'name' => 'Profile 5',
            'propertyType' => 'typeA',
            'searchFields' => [
                'rooms' => [2,null],
                'price' => [null, 120000],
                "returnPotential" => [15,null]
            ]
        ]);

        $searchProfiles = new SearchProfileCollection(
            $searchProfile1,
            $searchProfile2,
            $searchProfile3,
            $searchProfile4,
            $searchProfile5
        );

        $matchedSearchProfiles = $searchProfiles->matchingProperty($property)->toMatchedSearchProfilesCollection();


        $this->assertEquals($matchedSearchProfiles->count(),3);
        $matchedSearchProfile1 = $matchedSearchProfiles->findBySearchProfileId(1);
        $matchedSearchProfile2 = $matchedSearchProfiles->findBySearchProfileId(4);
        $matchedSearchProfile3 = $matchedSearchProfiles->findBySearchProfileId(4);

        $this->assertNotNull($matchedSearchProfile1);
        $this->assertNotNull($matchedSearchProfile2);

        $this->assertEquals($matchedSearchProfile1->getStrictMatchesCount(), 2);
        $this->assertEquals($matchedSearchProfile1->getLooseMatchesCount(), 1);

        $this->assertEquals($matchedSearchProfile2->getStrictMatchesCount(), 2);
        $this->assertEquals($matchedSearchProfile2->getLooseMatchesCount(), 0);
    }
}
