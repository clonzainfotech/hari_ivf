<?php

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([

            ['role' => 'Admin'],
            ['role' => 'Reception'],
            ['role' => 'Doctor'],
            ['role' => 'Accountant'],
            ['role' => 'Medical'],
            ['role' => 'IVF'],
            ['role' => 'IUI'],
            ['role' => 'ANC'],
            ['role' => 'Developer'],
            ['role' => 'Telly Caller']
        ]);
    }
}
