<?php

namespace Database\Seeders;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($x = 0; $x <= 10; $x++)
        {
        DB::table('users')->insert([
            'first_name' => $faker->firstName,
            'email' => $faker->email,
            'password' => Hash::make("12345678"),
            'last_name' =>  $faker->lastName,
            'phone_number'=>$faker->phoneNumber,
            'points'=>0
        ]);
        }
    
    }
}
