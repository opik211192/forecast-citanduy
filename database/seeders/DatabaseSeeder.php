<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();
        $this->call(JbaratSeeder::class);
        $this->call(JtengahSeeder::class);
        $this->call(LokasiSeeder::class);
        $this->call(WeatherSeederr::class);
        $this->call(WindSeeder::class);
    }
}
