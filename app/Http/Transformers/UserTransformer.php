<?php
namespace App\Http\Transformers;


use App\Models\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'name'   => $user->name,
            'user_id'    => (int) $user->user_id,
            'email' => $user->email
        ];
    }
}