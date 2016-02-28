<?php

namespace App\Models\Scheme;

use App\Models\BaseModel;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Result extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'scheme_results';

    // TODO:: really it's fake key, because Eloquent doensn't support composite primary keys
    protected $primaryKey = 'result_id';

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo('App\Models\Scheme\Question');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Models\Scheme\Scheme');
    }

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

    public function scopeWhereScheme($query, Scheme $scheme)
    {
        return $query->where('scheme_id', '=', $scheme->getKey());
    }

    public function scopeWhereSection($query, Section $section)
    {
        return $query->whereIn('question_id', function ($query) use ($section) {
            return $query->select("question_id")->from('scheme_questions')->where('section_id', '=', $section->getKey());
        });
    }

    public function scopeWhereQuestion($query, Question $question)
    {
        return $query->where('question_id', '=', $question->getKey());
    }

    public function scopeWherePerson($query, Person $person)
    {
        return $query->where('person_id', '=', $person->getKey());
    }

    public function saveData($isNew)
    {
        if ($isNew) {
            return $this->createNew();
        } else {
            return $this->updateExisting();
        }
    }

    public function createNew()
    {
        return $this->save();
    }

    public function updateExisting()
    {
        DB::statement("UPDATE scheme_results SET result_text=:result_text, result_date=:result_date, result_number=:result_number, last_modified_by=:last_modified_by, last_modified_ts=:last_modified_ts WHERE person_id=:person_id AND question_id=:question_id",
            [
                'result_text' => $this->result_text,
                'result_date' => $this->result_date,
                'result_number' => $this->result_number,
                'last_modified_by' => $this->last_modified_by,
                'last_modified_ts' => Carbon::now(),
                'person_id' => $this->person_id,
                'question_id' => $this->question_id
            ]);
    }


}
