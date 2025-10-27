<?php

namespace Database\Seeders;

use App\Models\Client;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{

    public function run(): void
    {
        \App\Models\Client::factory()->count(10)->create();
    }
}