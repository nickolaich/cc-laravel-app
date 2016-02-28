<?php

namespace App\Models\Scheme;

use App\Models\BaseModel;

class Question extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scheme_questions';

    protected $primaryKey = 'question_id';


    public function section(){
        return $this->belongsTo('App\Models\Scheme\Section');
    }

    public function results(){
        return $this->hasMany('App\Models\Scheme\Result');
    }
}
