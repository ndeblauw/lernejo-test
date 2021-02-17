<?php

namespace Database\Seeders;

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
        \App\Models\Tenant::create([
            'name' => 'De Gazelle',
            'subdomain' => 'gazelle',
            'domain' => 'gazelle.test',
        ]);

        \App\Models\Tenant::create([
            'name' => 'Het Boszicht',
            'subdomain' => 'boszicht',
            'domain' => 'boszicht.test',
            'use_domain' => true,
        ]);


    }
}
