<?php

use Illuminate\Database\Seeder;
use App\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'admin';
        $user->email = 'info@clonzainfotech.com';
        $user->password = bcrypt('Clonza@2026!');
        $user->role = 1;
        $user->save();
    }
}
