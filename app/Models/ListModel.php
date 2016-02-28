<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ListModel extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lists';

    protected $primaryKey = 'list_id';

    public function values()
    {
        return $this->hasMany('App\Models\ListValue', 'list_id');
    }

    /**
     * Build simple list
     * @return array
     */
    public function simpleList(){
        $ret = [];
        if ($this->getKey()) {
            $rootList = $this->values()->root()->get();
            foreach ($rootList as $valueModel) {
                $ret[$valueModel->getKey()] = $valueModel->value1;
            };
        }
        return $ret;
    }

    /**
     * Build list if childs need
     * @return array
     */
    public function buildItems()
    {
        $ret = [];
        if ($this->getKey()) {
            $rootList = $this->values()->root()->get();
            foreach ($rootList as $valueModel) {
                $item = [
                    'id' => $valueModel->getKey(),
                    'value' => $valueModel->value1,
                    'text' => $valueModel->value1,
                    'childs' => []
                ];
                $childs = $this->values()->child($valueModel->value1)->get();
                $item['childs'][0] = [
                    'id' => 0,
                    'value' => '',
                    'text' => ''
                ];
                foreach ($childs as $childValueModel) {
                    $item['childs'][$childValueModel->getKey()] = [
                        'id' => $childValueModel->getKey(),
                        'value' => $childValueModel->getValue(),
                        'text' => $childValueModel->getValue()
                    ];
                }
                $ret[$valueModel->getKey()] = $item;
            };
        }
        return $ret;
    }

}
