<?php

namespace app\modules\auth\models;

use yii\db\ActiveRecord;
use yii\rbac\Item;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'description'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'type' => 'Тип',
            'description' => 'Описание',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasMany(AuthAssignment::class, ['item_name' => 'name']);
    }

    /**
     * @param $userId
     * @return array
     */
    public static function roleMap($userId = null)
    {
        $query = self::find();

        if ($userId) {
            $query->joinWith(['assignment'])->andWhere([AuthAssignment::tableName() . '.user_id' => $userId]);
        }

        $roles = $query->andWhere([self::tableName() . '.type' => Item::TYPE_ROLE])->all();

        return ArrayHelper::map($roles, 'name', 'description');
    }

}
