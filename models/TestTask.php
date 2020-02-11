<?php

namespace app\models;

use app\models\query\TestTaskQuery;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "test_task".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $status Статус
 * @property string $created_at Дата создания
 *
 * @property User $user
 * @property TestTaskQuestion[] $testTaskQuestions
 */
class TestTask extends ActiveRecord
{
    /**
     * Новый
     */
    const STATUS_NEW = 0;

    /**
     * Пройден
     */
    const STATUS_FINISHED = 1;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_task';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'statusLabel' => 'Статус',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[TestTaskQuestions]].
     *
     * @return ActiveQuery
     */
    public function getTestTaskQuestions()
    {
        return $this->hasMany(TestTaskQuestion::class, ['test_task_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TestTaskQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TestTaskQuery(get_called_class());
    }

    /**
     * @return array
     */
    public static function statusMap()
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_FINISHED => 'Пройден',
        ];
    }

    /**
     * @return int|mixed
     */
    public function getStatusLabel()
    {
        $map = self::statusMap();
        return isset($map[$this->status]) ? $map[$this->status] : $this->status;
    }
}
