<?php
namespace App\Http\Transformers\Scheme;


use App\Models\Scheme\Section;
use League\Fractal;

class SectionTransformer extends Fractal\TransformerAbstract
{
    public function transform(Section $model)
    {
        return [
            'section_id'=> $model->getKey(),
            'scheme_id'=> $model->scheme_id,
            'name'   => $model->section_name
        ];
    }
}
