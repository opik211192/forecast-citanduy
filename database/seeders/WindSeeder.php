<?php

namespace Database\Seeders;

use App\Models\Wind;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Wind::create([
            'Wind_direction' => 'N',
            'name' => 'North',
        ]);

         Wind::create([
            'Wind_direction' => 'NE',
            'name' => 'Northeast',
        ]);

         Wind::create([
            'Wind_direction' => 'E',
            'name' => 'East',
        ]);

         Wind::create([
            'Wind_direction' => 'SE',
            'name' => 'SouthEast',
        ]);

         Wind::create([
            'Wind_direction' => 'S',
            'name' => 'South',
        ]);

         Wind::create([
            'Wind_direction' => 'SW',
            'name' => 'Southwest',
        ]);

         Wind::create([
            'Wind_direction' => 'W',
            'name' => 'West',
        ]);

         Wind::create([
            'Wind_direction' => 'NW',
            'name' => 'Northwest',
        ]);
    }
}
