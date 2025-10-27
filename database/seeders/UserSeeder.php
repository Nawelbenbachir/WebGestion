<?php

namespace Database\Seeders;

use Illuminate\support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        //générateur de fausses données

       User::factory()->count(10)->create();
    }
}