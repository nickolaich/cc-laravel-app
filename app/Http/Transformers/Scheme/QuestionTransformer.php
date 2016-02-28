<?php
namespace App\Http\Transformers\Scheme;


use App\Models\ListModel;
use App\Models\Scheme\Question;
use League\Fractal;

class QuestionTransformer extends Fractal\TransformerAbstract
{
    public function transform(Question $model)
    {
        return [
            'question_id' => $model->getKey(),
            'name' => $model->script,
            'question_ref' => $model->question_ref,
            'type' => $this->_toFrontendType($model),
            'options' => $this->_getOptions($model),
            'settings' => $this->_getSettings($model),
        ];
    }

    protected function _getSettings($model)
    {
        $settings = [];
        if (in_array($model->data_type, ['Double Drop-down', 'Double Combo-Box'])) {
            $settings['label1'] = $model->dropdown_label1 ? $model->dropdown_label1 : ""/*$model->script*/
            ;
            $settings['label2'] = $model->dropdown_label2;
        } elseif (in_array($model->data_type, ['Single Drop-down', 'Single Combo-Box'])) {
            $settings['label'] = $model->dropdown_label1;
        }
        if ($model->colour) {
            $settings['colour'] = $model->colour;
        }
        return $settings;
    }

    /**
     * Get options for select etc
     * @param $model
     * @return array
     */
    protected function _getOptions($model)
    {
        $options = [];
        if ($model->list_id) {
            /** @var ListModel $listModel */
            $listModel = ListModel::find($model->list_id);
            if ($listModel) {
                if (in_array($model->data_type, ['Double Drop-down', 'Double Combo-Box'])) {
                    $options = $listModel->buildItems();
                } elseif (in_array($model->data_type, ['Single Drop-down', 'Single Combo-Box'])) {
                    $options = $listModel->simpleList();
                }
            }
        }
        return $options;
    }

    protected function _toFrontendType($model)
    {
        $default = 'Text';
        $assoc = [
            'Label' => 'Label',
            'Single-line Text' => 'Text',
            'Multi-line Text' => 'TextArea',
            'Single Drop-down' => 'Select',
            'Double Drop-down' => 'DoubleDropDown',
            'Appointment' => 'Text',
            'Date' => 'DatePicker',
            'Integer' => 'Text',
            'Decimal' => 'Text',
            'Note' => 'Text',
            'Double Combo-Box' => 'Text',
            'Single Combo-Box' => 'Text',
            'Orders' => 'Text'
        ];

        return isset($assoc[$model->data_type]) ? $assoc[$model->data_type] : $default;
    }
}
