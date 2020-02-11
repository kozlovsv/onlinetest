<?php

namespace app\models\query;

use app\models\TestTask;
use Yii;
use yii\db\ActiveQuery;

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