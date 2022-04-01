<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name'	    => 'Abdurrahman Shifa',
            'email'	    => 'abdurrahmanshifa@gmail.com',
            'password'	=> bcrypt('123'),
            'roles'     => 'admin',
            'status'    => 'aktif'
        ]);
    }
}
