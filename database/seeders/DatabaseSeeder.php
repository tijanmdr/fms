<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


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
        \App\Models\User::create(['name'=>"Admin", "email"=>'tijanmdr@gmail.com', "password"=>Hash::make('Password'), "access"=>0, "address"=>'26 Mannheim Street', "dob"=>'1994/09/18']);
    }
}
