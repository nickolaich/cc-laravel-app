<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{


    public function index(){
        return $this->response->collection(User::all(), new UserTransformer);
    }

    public function show($id){
        return $this->response->item(User::findOrFail($id), new UserTransformer);
    }

    public function store(){
        return User::create(Input::only("name", "email"));
    }

    public function update($id){
        User::findOrFail($id)->update(Input::only("name", "email"));
        return $this->show($id);
    }

    public function destroy($id){
        return User::destroy($id);
    }
}