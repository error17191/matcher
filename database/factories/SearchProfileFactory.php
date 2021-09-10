<?php

namespace Database\Factories;

use App\Matcher\FakeDataHelper;
use App\Models\SearchProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class SearchProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'propertyType' => FakeDataHelper::randomPropertyType(),
            'name' => $this->faker->sentence(),
            'searchFields' => FakeDataHelper::randomSearchProfileFields()
        ];
    }
}
