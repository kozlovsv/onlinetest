<?php


namespace app\widgets;


use kartik\form\ActiveField;
use yii\helpers\ArrayHelper;

class FormBuilder extends \kozlovsv\crud\widgets\FormBuilder
{
    /**
     * Делаем хак. В стандартной поставке KertikV поле INPUT_RADIO_BUTTON_GROUP рисуется горизонтально.
     * Но нам иногда нужно вертикально. Для этого мы жестко заданный класс btn-group меняем на btn-group-vertical.
     * Пробовал делать через виджеты, неудобно. Слишком много кода копипастить.
     *
     * @param $form
     * @param $model
     * @param $attribute
     * @param $settings
     * @return string
     */
    protected static function renderRawActiveInput($form, $model, $attribute, $settings)
    {
        $type = ArrayHelper::getValue($settings, 'type', self::INPUT_TEXT);
        $text = parent::renderRawActiveInput($form, $model, $attribute, $settings);
        if ($type == self::INPUT_RADIO_BUTTON_GROUP && $text instanceof ActiveField) {
            $options = ArrayHelper::getValue($settings, 'options', []);
            $class = ArrayHelper::getValue($options, 'class', []);
            if (!is_array($class))
                $class = explode(' ', $class);
            if (in_array('btn-group-vertical', $class)) {
                $text->parts = str_replace(' btn-group ', ' ', $text->parts);
            }
        }
        return $text;
    }

}