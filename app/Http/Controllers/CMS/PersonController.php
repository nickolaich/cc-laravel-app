<?php
namespace App\Http\Controllers\CMS;
use App\Events\PersonDataChanged;
use App\Http\Controllers\Controller;
use App\Http\Transformers\PersonTransformer;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PersonController extends Controller
{

  protected $_pageSize = 50;

    public function index(){
        $paginated = Person::paginate($this->_pageSize);
        return $this->response->paginator($paginated, new PersonTransformer);
        return $this->response->collection(Person::take(7)->get(), new PersonTransformer);
    }

    public function show($id){
        return $this->response->item(Person::findOrFail($id), new PersonTransformer);
    }

    public function store(){
        return User::create(Person::only("forename", "surname", "email"));
    }

    public function update($id){
        $person = Person::findOrFail($id);

        // Fire event for updating scheme
        event(new PersonDataChanged($person, Input::all()));

        return $this->show($id);
    }

    public function destroy($id){
        return Person::destroy($id);
    }

    public function search($keywords){
      $paginated = Person::where('forename', 'LIKE', '%' . $keywords . '%')
          ->orWhere('surname', 'LIKE', '%' . $keywords . '%')
          ->paginate($this->_pageSize);
      return $this->response->paginator($paginated, new PersonTransformer);
    }
}
