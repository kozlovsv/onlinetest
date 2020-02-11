<?php

namespace app\modules\auth\models\form;

use yii\base\Model;

/**
 * Class RbacRoleForm
 * @package backend\modules\auth\models\form
 */
class RbacRoleForm extends Model
{
    /**
     *
     * @var string
     */
    public $description;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'unique', 'targetClass' => 'backend\modules\auth\models\AuthItem', 'message' => 'Такая роль уже существует'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'description' => 'Название',
        ];
    }

}
