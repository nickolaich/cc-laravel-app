<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{


    public function scopeValid($query, $valid = 'y'){
        return $query->where('is_valid', '=', $valid);
    }
}
