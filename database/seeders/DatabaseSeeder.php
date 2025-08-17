<?php

namespace Database\Seeders;

use App\Services\AppSeeder;
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
        AppSeeder::seedPayMode();
        AppSeeder::seedPermissions();
        AppSeeder::seedRoles();
        AppSeeder::seedDefaultWarehouse();
        AppSeeder::seedDefaultTax();
        AppSeeder::seedUnits();
        AppSeeder::seedCompany();
        AppSeeder::seedUser();
        AppSeeder::seedVariants();
        AppSeeder::seedColor();
        AppSeeder::seedSize();
    }
}
