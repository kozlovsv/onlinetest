<?php

use app\modules\auth\models\AuthItem;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$form = ActiveForm::begin();

$attributes =  [
    'login:fa:user',
    'name:fa:user',
    'email:fa:user',
];

if (Yii::$app->user->can('auth.manage')) {
    $attributes['roles'] = [
        'type' => FormBuilder::INPUT_CHECKBOX_LIST,
        'items' => AuthItem::roleMap(),
    ];
}


echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
echo FormBuilder::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => $attributes,
    ]
);

echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::saveButton(),
            CrudButton::cancelButton(),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin-top: 20px; margin-right: 0'],
    ]
);

ActiveForm::end();