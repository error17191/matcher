<?php


namespace App\Matcher;


class MatchedSearchProfileCollection
{
    private $matchedSearchProfiles;

    public function __construct(MatchedSearchProfile ...$matchedSearchProfiles)
    {
        $this->matchedSearchProfiles = collect($matchedSearchProfiles)->keyBy(function (MatchedSearchProfile $machedSearchProfile) {
            return $machedSearchProfile->getSearchProfileId();
        });
    }

    public function count()
    {
        return $this->matchedSearchProfiles->count();
    }

    public function toResponse()
    {
        return $this->matchedSearchProfiles->map(function (MatchedSearchProfile $matchedSearchProfile) {
            return [
                'searchProfileId' => $matchedSearchProfile->getSearchProfileId(),
                'score' => $matchedSearchProfile->getScore(),
                'strictMatchesCount' => $matchedSearchProfile->getStrictMatchesCount(),
                'looseMatchesCount' => $matchedSearchProfile->getLooseMatchesCount(),
            ];
        })->values()->all();
    }

    public function findBySearchProfileId($id): ?MatchedSearchProfile
    {
        return $this->matchedSearchProfiles->get($id);
    }

    public function sortByScore()
    {
        $this->matchedSearchProfiles = $this->matchedSearchProfiles->sortByDesc(function (MatchedSearchProfile $matchedSearchProfile) {
            return $matchedSearchProfile->getScore();
        },);

        return $this;
    }
}
