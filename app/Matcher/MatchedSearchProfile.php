<?php


namespace App\Matcher;


class MatchedSearchProfile
{
    private $searchProfileId;
    private $strictMatches;
    private $looseMatches;

    public function __construct($searchProfileId, array $strictMatches, array $looseMatches)
    {
        $this->searchProfileId = $searchProfileId;
        $this->strictMatches = $strictMatches;
        $this->looseMatches = $looseMatches;
    }

    public function getSearchProfileId()
    {
        return $this->searchProfileId;
    }

    public function getStrictMatches()
    {
        return $this->strictMatches;
    }

    public function getStrictMatchesCount()
    {
        return count($this->strictMatches);
    }

    public function getLooseMatches()
    {
        return $this->looseMatches;
    }

    public function getLooseMatchesCount()
    {
        return count($this->looseMatches);
    }

    public function getScore()
    {
        return $this->getStrictMatchesCount() + 0.75 * $this->getLooseMatchesCount();
    }
}
