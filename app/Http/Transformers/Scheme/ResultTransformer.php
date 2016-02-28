<?php
namespace App\Http\Transformers\Scheme;


use App\Models\Scheme\Result;
use App\Models\Scheme\Section;
use League\Fractal;

class ResultTransformer extends Fractal\TransformerAbstract
{
    public function transform(Result $model)
    {
        return [
            'result_id'=> sprintf("%s_%s_%s", $model->scheme_id, $model->question_id, $model->person_id),
            'question_id'   => $model->question_id,
            'value'   => $this->_getValue($model),
            'result_text'   => $model->result_text,
            'result_date'   => $model->result_date,
            'result_number'   => $model->result_number
        ];
    }

    protected function _getValue($model){
      $question = $model->question;
      if ($question){
        if (in_array($model->question->data_type, ['Double Drop-down', 'Double Combo-Box'])){
          $value = explode('|', $model->result_text);
          if (count($value) != 2){
            $value = ['', ''];
          }
        } else {
          $value = $model->result_text;
        }
      } else {
        $value = null;
      }
      return $value;
    }
}
