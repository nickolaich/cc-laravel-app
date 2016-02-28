<?php
namespace App\Http\Controllers;

use App\Http\Transformers\AuthTransformer;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{


    public function __construct()
    {
        //$this->middleware('token', ['except' => 'login']);
    }

    /**
     * Login to system
     * @return \Dingo\Api\Http\Response
     */
    public function login()
    {
        if (!Input::get('email') || !Input::get('password')) {
            return response()->json(trans("auth.invalid_credentials_1"), 403);
        }


        $deviceId = Input::get('device_id', md5(str_random()) . uniqid());

        $user = User::whereEmail(Input::get('email'))->first();
        if (!$user) {
            return response()->json(trans("auth.invalid_credentials_2"), 403);
        }
        if (!Hash::check(Input::get('password'), $user->password)) {
            return response()->json(trans("auth.invalid_credentials_3"), 403);
        }

        $token = $user->createToken($deviceId, Input::get('device_type'));
        return $this->response->item($token, new AuthTransformer);
    }

    /**
     * Logout
     * @param $token
     * @return \Dingo\Api\Http\Response
     */
    public function logout($token)
    {
        $token = urldecode($token);
        $tokenQuery = UserToken::whereToken($token);
        if ($tokenQuery->count()){
            $tokenQuery->forceDelete();
            return response()->json(trans("auth.logged_out"));
        }
        return response()->json(trans("auth.invalid_token"), 403);
    }

    /**
     * Validate auth token
     * @param \Illuminate\Http\Request $token
     * @return static
     */
    public function valid($token)
    {
        if (UserToken::whereToken($token)->count()){
            return response()->json("OK", 200);
        } else {
            return response()->json(trans("auth.invalid_token"), 403);
        }
    }

}
