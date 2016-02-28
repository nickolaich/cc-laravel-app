<?php

use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{

    use DatabaseMigrations;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        //$this->runDatabaseMigrations();
        //$this->seed(\UserTableSeeder::class);
        $this->auth();
        $this->_transformer = new UserTransformer();

    }

    /**
     * Test user transformation before sending response to client.
     * @return void
     */
    public function testUserTransformer()
    {
        $user = new User([
            "name" => "Test 1",
            "user_id" => 1111,
            "email" => "test@com.com",
            "password" => "1234546457yrthr",
            "custom_attr" => true
        ]);
        $t = $this->_transformer->transform($user);
        $this->assertArrayHasKey("name", $t);
        $this->assertArrayHasKey("user_id", $t);
        $this->assertArrayHasKey("email", $t);
        $this->assertArrayNotHasKey("password", $t);
        $this->assertArrayNotHasKey("custom_attr", $t);
        $this->assertCount(3, $t);
    }

    /**
     * Test users listing
     * @return void
     */
    public function testUserIndex()
    {
        $this->get('/users');
        $this->seeStatusCode(200);
        // Check if response contains newly created user
        $this->seeJson($this->_transformer->transform($this->_user));
    }

    /**
     * Test fetching information about 1 user
     * @return void
     */
    public function testUserShow()
    {
        $testUser = factory(User::class)->create();
        $this->get('/users/' . $testUser->getKey());
        $this->seeStatusCode(200);
        // Check if response contains newly created user
        $this->seeJsonEquals($this->_transformer->transform($testUser));

        // Check not existing user
        $this->get('/users/99999');
        $this->dump();
        $this->seeStatusCode(404);
    }

    /**
     * Test updating information about user
     * @return void
     */
    public function testUserUpdate()
    {
        $testUser = factory(User::class)->create();
        $this->put('/users/' . $testUser->getKey(), ["name" => $testName = "Super User Name"]);
        $this->seeStatusCode(200);

        $checkUser = User::findOrFail($testUser->getKey());
        $this->assertEquals($checkUser->name, $testName);

    }

    /**
     * Test deleting user
     * @return void
     */
    public function testUserDelete()
    {
        $testUser = factory(User::class)->create();
        $this->delete('/users/' . $testUser->getKey());
        $this->seeStatusCode(200);
        $checkUser = User::find($testUser->getKey());
        $this->assertNull($checkUser);

        // Check if it was trashed
        $checkUser = User::withTrashed()->find($testUser->getKey());
        $this->assertEquals($checkUser->id, $testUser->id);
    }
}
