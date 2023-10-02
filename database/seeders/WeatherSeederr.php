<?php

namespace Database\Seeders;

use App\Models\Weather;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeatherSeederr extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Weather::create([
            'Weather_code' => 0,
            'name' => 'Cerah' 
        ]);

        Weather::create([
            'Weather_code' => 1,
            'name' => 'Cerah Berawan' 
        ]);

        Weather::create([
            'Weather_code' => 2,
            'name' => 'Cerah Berawan' 
        ]);

        Weather::create([
            'Weather_code' => 3,
            'name' => 'Berawan' 
        ]);

        Weather::create([
            'Weather_code' => 4,
            'name' => 'Berawan Tebal' 
        ]);

        Weather::create([
            'Weather_code' => 5,
            'name' => 'Udara Kabur' 
        ]);

        Weather::create([
            'Weather_code' => 10,
            'name' => 'Asap' 
        ]);

        Weather::create([
            'Weather_code' => 45,
            'name' => 'Kabut' 
        ]);

        Weather::create([
            'Weather_code' => 60,
            'name' => 'Hujan Ringan' 
        ]);

        Weather::create([
            'Weather_code' => 61,
            'name' => 'Hujan Sedang' 
        ]);

        Weather::create([
            'Weather_code' => 63,
            'name' => 'Hujan Lebat' 
        ]);

        Weather::create([
            'Weather_code' => 80,
            'name' => 'Hujan Lokal' 
        ]);

        Weather::create([
            'Weather_code' => 95,
            'name' => 'Hujan Petir' 
        ]);

        Weather::create([
            'Weather_code' => 97,
            'name' => 'Hujan Petir' 
        ]);

          Weather::create([
            'Weather_code' => 500,
            'name' => 'Tidak diketahui' 
        ]);
    }
}
