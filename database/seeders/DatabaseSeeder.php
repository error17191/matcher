<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\SearchProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Property::factory()->count(20)->create();
        SearchProfile::factory()->count(2000)->create();
    }
}
