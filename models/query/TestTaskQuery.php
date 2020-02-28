<?php

namespace app\models\query;

use app\models\TestTask;
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