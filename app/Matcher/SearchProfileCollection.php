<?php


namespace App\Matcher;


use App\Models\Property;
use App\Models\SearchProfile;

class SearchProfileCollection
{
    private $searchProfiles;
    private $matchers = [];

    public function __construct(SearchProfile ...$profiles)
    {
        $this->searchProfiles = collect($profiles);
    }

    /**
     * Filters the searchProfiles and keep only ones that matching property
     *
     * @param Property $property
     */
    public function matchingProperty(Property $property)
    {
        $this->searchProfiles->filter(function ($searchProfile) use ($property) {
            $matcher = PropertySearchProfileMatcher::create($property, $searchProfile);
            if ($matcher->areMatching()) {
                $this->matchers[] = $matcher;
                return true;
            }

            return false;
        });
        return $this;
    }

    public function toMatchedSearchProfiles()
    {
        return new MatchedSearchProfileCollection(...collect($this->matchers)->map(function (PropertySearchProfileMatcher $matcher) {
            return new MatchedSearchProfile($matcher->getSearchProfile()->id, $matcher->strictMatchingFields(), $matcher->looseMatchingFields());
        })->all());
    }
}
