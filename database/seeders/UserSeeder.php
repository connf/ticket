<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->generateNewUserData(5);
    }

    // In case we want to limit the generation of data to our own validation eg setting our
    // own list of names or emails being built with the names we generate
    // we can do this manually instead of via factories or seeders
    private function generateNewUserData($times = 1) {
        // for the number of $times that we choose 
        for ($i = 1; $i <= $times; $i++) {
            // create a set of data as per our requirements
            $firstName = ucwords($this->name(rand(1, 10)));
            $lastName = ucwords($this->name(rand(1, 10)));
            $email = $firstName.".".$lastName."@email.com";

            // then pass that data to the seeder or factory within our private function
            // overriding the default factory with our own semi-controlled yet still random 
            // data
            \App\Models\User::factory()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => bcrypt('password'),
            ]);
        }
    }

    // Stole a quick name generator from laracasts and modified it a bit
    // Otherwise we'd have numbers etc that could be in a random string
    function name($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
