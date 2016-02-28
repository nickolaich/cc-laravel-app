<?php
namespace App\Http\Transformers;


use App\Models\Person;
use League\Fractal;

class PersonTransformer extends Fractal\TransformerAbstract
{
    public function transform(Person $model)
    {
        return [
            'name'   => $model->forename . " " . $model->surname,
            'person_id'    => (int) $model->person_id,
            'email' => $model->email,
            'group_ref' => $model->group_ref,
            'gender' => $model->gender,
            'title' => $model->title
        ];
    }
}
