<?php

namespace App\Http\Controllers;

use App\Matcher\SearchProfileCollection;
use App\Models\Property;
use App\Models\SearchProfile;

class MatchingController extends Controller
{
    public function __invoke(Property $property)
    {
        $searchProfiles = SearchProfile::ofType($property->getPropertyType())->get();
        $searchProfilesCollection =  new SearchProfileCollection(...$searchProfiles->all());
        $matchedSearchProfilesCollection = $searchProfilesCollection->matchingProperty($property)->toMatchedSearchProfilesCollection();
        return [
            'data' => $matchedSearchProfilesCollection->sortByScore()->toResponse(),
            'total' => $matchedSearchProfilesCollection->count()
        ];
    }
}
