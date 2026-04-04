<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create seller accounts
        User::firstOrCreate(
            ['email' => 'mike.goco@urios.edu.ph'],
            [
                'first_name' => 'Mike',
                'last_name' => 'Goco',
                'username' => 'mikegoco',
                'name' => 'Mike Goco',
                'password' => Hash::make('mikeangelo'),
                'role' => 'seller',
            ]
        );

        User::firstOrCreate(
            ['email' => 'trish.castillo@urios.edu.ph'],
            [
                'first_name' => 'Trish',
                'last_name' => 'Castillo',
                'username' => 'trishcastillo',
                'name' => 'Trish Castillo',
                'password' => Hash::make('trishaaa'),
                'role' => 'seller',
            ]
        );
    }
}
