<?php

use App\Models\User;
use App\Models\UserToken;


class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://ccpn-api/v1';

    protected $_apiPrefix = '/v1/';

    /**
     * @var UserToken
     */
    protected $_token;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @var
     */
    protected $_transformer;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->loadEnvironmentFrom(".env." . getenv('APP_ENV'));

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function getJson($keyPath, $response = null){
        if (!$response){
            $response = $this->response;
        }
        $arr = (array)json_decode($response->getContent());
        return array_get($arr, $keyPath);
    }

    public function auth($user = null){
        if ($user instanceof User){
            $this->_user = $user;
        } else {
            // Add 1 test user
            $this->_user = factory(User::class)->create([
                'password' => bcrypt('password')
            ]);
        }
        $this->_token = $this->_user->createToken(null, "phpunit");
        $this->be($this->_user);
    }

}
