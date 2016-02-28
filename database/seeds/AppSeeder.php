<?php

use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 5)->create();

        // Add 1 test user
        factory(App\Models\User::class)->create([
            'email' => 'test@api.com',
            'password' => bcrypt('password')
        ]);
    }
}
