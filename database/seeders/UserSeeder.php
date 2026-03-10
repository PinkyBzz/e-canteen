<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin Kantin',
            'email'    => 'admin@ecanteen.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'balance'  => 0,
        ]);

        // Sample users
        $users = [
            ['name' => 'Budi Santoso',  'email' => 'budi@ecanteen.com',  'balance' => 50000],
            ['name' => 'Siti Rahayu',   'email' => 'siti@ecanteen.com',  'balance' => 75000],
            ['name' => 'Ahmad Fauzi',   'email' => 'ahmad@ecanteen.com', 'balance' => 100000],
            ['name' => 'Dewi Lestari',  'email' => 'dewi@ecanteen.com',  'balance' => 30000],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
                'balance'  => $user['balance'],
            ]);
        }
    }
}
