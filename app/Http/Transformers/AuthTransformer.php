<?php
namespace App\Http\Transformers;


use App\Models\UserToken;
use League\Fractal;
use League\Fractal\Resource\Item;

class AuthTransformer extends Fractal\TransformerAbstract
{

    protected $defaultIncludes = ['user'];

    public function transform(UserToken $token)
    {
        return [
            'token'   => $token->token,
            'user_id' => (int) $token->user_id,
            'device_id' => $token->device_id,
            'email' => $token->user->email
        ];
    }

    public function includeUser(UserToken $token)
    {
      return new Item($token->user, new UserTransformer);
    }
}
