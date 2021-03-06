<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 5)->create();
        if (app()->environment() === 'local' && !User::whereEmail('test@api.com')->first()){
            // Add test user
            factory(User::class)->create([
                'email' => 'test@api.com',
                'password' => bcrypt('password')
            ]);
        }

    }
}
