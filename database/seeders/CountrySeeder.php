<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CountrySeeder extends Seeder
{
    public function run()
    {
        $usaStates = [
            "AL" => "Alabama",
            "AK" => "Alaska",
            "AZ" => "Arizona",
            "CA" => "California",
            "FL" => "Florida",
            "NY" => "New York",
            "TX" => "Texas",
            "WA" => "Washington",
        ];

        $cambodiaStates = [
            "PP" => "Phnom Penh",
            "KD" => "Kandal",
            "SR" => "Siem Reap",
            "BT" => "Battambang",
            "KC" => "Kampong Cham",
            "KP" => "Kampot",
        ];

        $countries = [
            [
                'code' => 'us',
                'name' => 'United States',
                'states' => json_encode($usaStates)
            ],
            [
                'code' => 'kh',
                'name' => 'Cambodia',
                'states' => json_encode($cambodiaStates)
            ],
        ];

        DB::table('countries')->insert($countries);
    }
}
