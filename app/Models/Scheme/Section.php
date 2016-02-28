<?php

namespace App\Models\Scheme;

use App\Models\BaseModel;

class Section extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scheme_sections';

    protected $primaryKey = 'section_id';


    public function scheme(){
        return $this->belongsTo('App\Models\Scheme\Scheme');
    }

    public function questions(){
        return $this->hasMany('App\Models\Scheme\Question');
    }

}
