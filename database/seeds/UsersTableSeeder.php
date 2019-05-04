<?php

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
        App\Models\User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('secret')
        ]);
    }
}
