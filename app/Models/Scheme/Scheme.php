<?php

namespace App\Models\Scheme;

use App\Models\BaseModel;

class Scheme extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schemes';

    protected $primaryKey = 'scheme_id';


    public function sections(){
        return $this->hasMany('App\Models\Scheme\Section');
    }

}
