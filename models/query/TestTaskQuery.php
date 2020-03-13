<?php

namespace app\models\query;

use app\models\TestTask;
use app\modules\auth\models\AuthItem;
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
            ->andWhere([AuthItem::tableName() . '.name' => $roleName]);
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
     * Текущий день
     * @return $this
     */
    public function finished()
    {
        return $this->andWhere(['status' => TestTask::STATUS_FINISHED]);
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