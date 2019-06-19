<?php

use Illuminate\Database\Seeder;
use App\Role;
class permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Role::truncate();

        Role::create(['user_id' => '1', 'has_administration_rights' => '1', 'has_onboarding_rights' => 0]);
        Role::create(['user_id' => '2', 'has_administration_rights' => '1', 'has_onboarding_rights' => 0]);
        Role::create(['user_id' => '3', 'has_administration_rights' => '1', 'has_onboarding_rights' => 0]);
        Role::create(['user_id' => '4', 'has_administration_rights' => '1', 'has_onboarding_rights' => 0]);
        Role::create(['user_id' => '5', 'has_administration_rights' => '1', 'has_onboarding_rights' => 0]);
    }
}
