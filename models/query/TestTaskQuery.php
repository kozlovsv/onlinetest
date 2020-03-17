<?php

namespace app\models\query;

use app\models\TestTask;
use app\modules\auth\models\AuthAssignment;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\app\models\TestTask]].
 *
 * @see \app\models\TestTask
 */
class TestTaskQuery extends ActiveQuery
{
    /**
     * Свой тест
     * @return $this
     */
    public function own()
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

    /**
     * По роли
     * @param $roleName
     * @return $this
     */
    public function byUserRole($roleName)
    {
        $this->joinWith(['user', 'user.roles'])
            ->andWhere([AuthAssignment::tableName() . '.item_name' => $roleName]);
        return $this;
    }

    /**
     * Текущий день
     * @return $this
     */
    public function passedToday()
    {
        return $this->andWhere(['date(passed_at)' => new Expression('CURDATE()')]);
    }

    /**
     * Завершенные
     * @return $this
     */
    public function finished()
    {
        return $this->andWhere(['status' => TestTask::STATUS_FINISHED]);
    }

    /**
     * Контрольная?
     * @param int $is_repetition
     * @return $this
     */
    public function repetition($is_repetition = 1)
    {
        return $this->andWhere(['is_repetition' => $is_repetition]);
    }

    /**
     * @inheritdoc
     * @return TestTask[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TestTask|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}