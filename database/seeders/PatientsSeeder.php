<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Faker\Factory as Faker;

class PatientsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('en_KE'); // Kenyan locale
        $wards = ['General Ward', 'Maternity', 'Surgical', 'Pediatric', 'ICU', 'Oncology', 'Orthopedic', 'ENT', 'Eye Unit'];
        $genders = ['Male', 'Female'];
        $kenyanFirstNamesMale = ['Brian', 'Kipkirui', 'Vincent', 'Amos', 'Paul', 'Joshua', 'David', 'Samuel', 'Joseph', 'Daniel'];
        $kenyanFirstNamesFemale = ['Faith', 'Mercy', 'Grace', 'Joyce', 'Mary', 'Esther', 'Beatrice', 'Ann', 'Jane', 'Sarah'];
        $kenyanLastNames = ['Kiprop', 'Cheruiyot', 'Kipchoge', 'Chebet', 'Jepchirchir', 'Langat', 'Kiptoo', 'Jepkosgei', 'Rotich', 'Ngetich'];

        for ($i = 0; $i < 520; $i++) {
            $gender = $genders[array_rand($genders)];
            $firstName = $gender === 'Male'
                ? $kenyanFirstNamesMale[array_rand($kenyanFirstNamesMale)]
                : $kenyanFirstNamesFemale[array_rand($kenyanFirstNamesFemale)];
            $lastName = $kenyanLastNames[array_rand($kenyanLastNames)];

            Patient::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'phone' => '07' . rand(10, 99) . rand(100000, 999999),
                'gender' => $gender,
                'date_of_birth' => $faker->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
                'nhif_number' => 'NHIF' . rand(1000000, 9999999),
                'ward' => $wards[array_rand($wards)],
                'is_admitted' => rand(0, 1),
            ]);
        }
    }
}
