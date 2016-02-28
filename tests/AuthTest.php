<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

    //use DatabaseMigrations;
    //use WithoutMiddleware;
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(App\Models\User::class)->create([
            'password'=>bcrypt($pwd = str_random(10))
        ]);

        // Use not all data
        $response = $this->call('POST', '/login', ['email' => $user->email]);
        $this->seeStatusCode(403);
        $response = $this->call('POST', '/login', ['password' => 'sds']);
        $this->seeStatusCode(403);

        // Use invalid email (no user)
        $response = $this->call('POST', '/login', ['email' => 'wrong', 'password' => 'sds']);
        $this->seeStatusCode(403);

        // Use invalid password
        $response = $this->call('POST', '/login', ['email' => $user->email, 'password' => $pwd . '!']);
        $this->seeStatusCode(403);

        // Valid credentials. Should be a user at response
        $response = $this->call('POST', '/login', ['email' => $user->email, 'password' => $pwd]);
        $this->seeStatusCode(200);
        $this->seeJson(["user_id" => $user->user_id, "email"=>$user->email]);
        $token = $this->getJson('token');
        $this->seeInDatabase('user_tokens', ['token'=>$token]);
    }

    public function testLogout()
    {
        $user = factory(App\Models\User::class)->create([
            'password' => bcrypt($pwd = str_random(10))
        ]);

        // Login
        $response = $this->call('POST', '/login', ['email' => $user->email, 'password' => $pwd]);
        $this->assertResponseOk();

        $token = $this->getJson('token');
        $deviceId = $this->getJson('device_id');


        // Call with wrong token.
        // Add it to request too for validation
        $response = $this->call('DELETE', '/logout/'.$token."!");
        $this->seeStatusCode(403);//Invalid token
        $this->seeInDatabase('user_tokens', ['token'=>$token]);


        // Call with valid params
        $response = $this->call('DELETE', '/logout/'.$token);
        $this->assertResponseOk();
        $this->notSeeInDatabase('user_tokens', ['token'=>$token]);

    }

    public function testValid()
    {
        $user = factory(App\Models\User::class)->create([
            'password' => bcrypt($pwd = str_random(10))
        ]);

        // Login
        $this->call('POST', '/login', ['email' => $user->email, 'password' => $pwd]);
        $this->assertResponseOk();

        $token = $this->getJson('token');
        // Check if token exists at db
        $this->seeInDatabase('user_tokens', ['token'=>$token]);

        // Call with wrong token
        $this->call('GET', '/valid/'.$token."-");
        $this->seeStatusCode(403);

        // Call with valid params
        $this->call('GET', '/valid/'.$token);
        $this->assertResponseOk();

    }
}
