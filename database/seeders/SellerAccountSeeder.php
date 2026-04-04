<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerAccountSeeder extends Seeder
{
    /**
     * Seed fixed seller accounts.
     *
     * This keeps seller access controlled to only approved accounts.
     *
     * @return void
     */
    public function run()
    {
        $sellers = [
            [
                'first_name' => 'Mike',
                'last_name' => 'Goco',
                'name' => 'Mike Goco',
                'email' => 'mike.goco@urios.edu.ph',
                'username' => 'mikegoco',
                'password' => 'mikeangelo',
            ],
            [
                'first_name' => 'Trish',
                'last_name' => 'Castillo',
                'name' => 'Trish Castillo',
                'email' => 'trish.castillo@urios.edu.ph',
                'username' => 'trishcastillo',
                'password' => 'trishaaa',
            ],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'first_name' => $seller['first_name'],
                    'last_name' => $seller['last_name'],
                    'name' => $seller['name'],
                    'username' => $seller['username'],
                    'password' => Hash::make($seller['password']),
                    'role' => 'seller',
                ]
            );
        }
    }
}
