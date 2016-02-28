<?php
namespace App\Http\Controllers;
use App\Http\Transformers\Scheme\QuestionTransformer;
use App\Http\Transformers\Scheme\ResultTransformer;
use App\Http\Transformers\Scheme\SectionTransformer;
use App\Models\Person;
use App\Models\Scheme\Scheme;
use App\Models\Scheme\Section;

class SchemeController extends Controller
{


    /**
     * Get list of sections by scheme
     * @param Scheme $scheme
     * @return \Dingo\Api\Http\Response
     */
    public function sections(Scheme $scheme){
        return $this->response->collection($scheme->sections()->orderBy('sort_order')->get(), new SectionTransformer);
    }

    /**
     * Get question of scheme
     * @param Section $section
     */
    public function questions(Section $section){
        return $this->response->collection($section->questions()->orderBy('question_nr')->get(), new QuestionTransformer);
    }


    /**
     * Generate form data
     * @param Scheme $scheme
     */
    public function form(Scheme $scheme){
        arrdd($scheme->sections()->with(['questions' => function($query){return $query->valid()->orderBy('sort_order');}])->orderBy('sort_order')->valid()->get()->toArray());
    }

    /**
     * @param Person $person
     */
    public function data(Scheme $scheme, Section $section, Person $person){
        $data = $person->results()->whereScheme($scheme)->whereSection($section)->get();
        return $this->response->collection($data, new ResultTransformer);
    }


}
