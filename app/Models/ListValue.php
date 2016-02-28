<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ListValue extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'list_values';

    protected $primaryKey = 'list_value_id';

    public function list_model(){
        return $this->belongsTo('App\Models\ListModel');
    }

    /**
     * Scope for fetching only main list
     * @param $query
     * @return mixed
     */
    public function scopeRoot($query){
        return $query->groupBy('value1');
    }

    /**
     * Scope for fetching sublist values
     * @param $query
     * @param $parent
     * @return mixed
     */
    public function scopeChild($query, $parent){
        return $query->whereRaw('LENGTH(trim(value2)) > 0')->where('value1', '=', $parent);
    }

    /**
     * Get value. For root - value1, for child value2
     */
    public function getValue(){
        return $this->value2 ? $this->value2 : $this->value1;
    }

}
