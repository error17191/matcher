<?php


namespace App\Matcher;


use App\Models\Property;
use App\Models\SearchProfile;

class PropertySearchProfileMatcher
{
    const DEVIATION = 0.25;

    private $property;
    private $searchProfile;

    private $looseMatches = [];
    private $strictMatches = [];

    public function __construct(Property $property, SearchProfile $searchProfile)
    {
        $this->property = $property;
        $this->searchProfile = $searchProfile;
    }

    public static function create(Property $property, SearchProfile $searchProfile)
    {
        return new self($property, $searchProfile);
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function getSearchProfile()
    {
        return $this->searchProfile;
    }

    public function areMatching()
    {
        if (!$this->areOfSameType()) {
            return false;
        }

        if ($this->propertyHasMissingFields()) {
            return false;
        }
        try {
            $this->checkFieldsMatching();
        } catch (FieldMismatchingException $exception) {
            return false;
        }

        return count($this->strictMatches) + count($this->looseMatches) > 0;
    }

    public function strictMatchingFields()
    {
        return $this->strictMatches;
    }

    public function looseMatchingFields()
    {
        return $this->looseMatches;
    }

    protected function areOfSameType()
    {
        return $this->property->getPropertyType() == $this->searchProfile->getSearchProfilePropertyType();
    }

    protected function propertyHasMissingFields()
    {
        return collect($this->searchProfile->getSearchProfileFields())->keys()
                ->diff(collect($this->property->getPropertyFields())->whereNotNull()->keys())->count() > 0;
    }

    protected function checkFieldsMatching()
    {
        $searchProfileFields = $this->searchProfile->getSearchProfileFields();
        $propertyFields = collect($this->property->getPropertyFields());

        foreach ($searchProfileFields as $searchField => $searchFieldValue) {
            $propertyFieldValue = $propertyFields->get($searchField);

            if (is_array($searchFieldValue)) {
                $this->checkRangeFieldMatching($searchField, $propertyFieldValue, $searchFieldValue);
                continue;
            }

            if ($searchFieldValue == $propertyFieldValue) {
                $this->strictMatches[] = $searchField;
                continue;
            }

            throw new FieldMismatchingException();
        }
    }

    protected function checkRangeFieldMatching($fieldName, $propertyFieldValue, $searchProfileFieldValue)
    {
        $lowerLimit = collect($searchProfileFieldValue)->get(0);
        $upperLimit = collect($searchProfileFieldValue)->get(1);

        if ($this->rangeStrictlyMatch($propertyFieldValue, $lowerLimit, $upperLimit)) {
            $this->strictMatches[] = $fieldName;
            return;
        }

        if ($this->rangeLooselyMatch($propertyFieldValue, $lowerLimit, $upperLimit)) {
            $this->looseMatches[] = $fieldName;
            return;
        }

        throw new FieldMismatchingException();
    }

    protected function rangeStrictlyMatch($value, $lowerLimit, $upperLimit)
    {
        return $this->isAnyConditionTrue([
            is_null($lowerLimit) && is_null($upperLimit),
            is_null($lowerLimit) && $value <= $upperLimit,
            is_null($upperLimit) && $value >= $lowerLimit,
            $value >= $lowerLimit && $value <= $upperLimit
        ]);
    }

    protected function rangeLooselyMatch($value, $lowerLimit, $upperLimit)
    {
        return $this->isAnyConditionTrue([
            is_null($lowerLimit) && $value <= $this->upperLimitWithDeviation($upperLimit),
            is_null($upperLimit) && $value >= $this->lowerLimitWithDeviation($lowerLimit),
            $value >= $this->lowerLimitWithDeviation($lowerLimit) && $value <= $this->upperLimitWithDeviation($upperLimit)
        ]);
    }

    protected function isAnyConditionTrue(array $condtions)
    {
        foreach ($condtions as $condtion) {
            if ($condtion) {
                return true;
            }
        }
        return false;
    }

    protected function upperLimitWithDeviation($value)
    {
        return $value * (1 + self::DEVIATION);
    }

    protected function lowerLimitWithDeviation($value)
    {
        return $value * (1 - self::DEVIATION);
    }
}
