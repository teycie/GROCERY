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
                'name' => 'Mike Goco',
                'email' => 'mike.goco@urios.edu.ph',
                'password' => 'mikeangelo',
            ],
            [
                'name' => 'Trish Castillo',
                'email' => 'trish.castillo@urios.edu.ph',
                'password' => 'trishaaa',
            ],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'name' => $seller['name'],
                    'password' => Hash::make($seller['password']),
                    'role' => 'seller',
                ]
            );
        }
    }
}
