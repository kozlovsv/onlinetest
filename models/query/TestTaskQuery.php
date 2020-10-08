<?php

namespace app\models\query;

use app\models\TestTask;
use kozlovsv\crud\modules\auth\models\AuthAssignment;
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
        return $this->andWhere([TestTask::tableName().'.user_id' => Yii::$app->user->id]);
    }

    /**
     * Выборка по оценке
     * @param int $grade
     * @return TestTaskQuery
     */
    public function grade($grade)
    {
        if (empty($grade)) return $this;
        $field = TestTask::tableName().'.rating';
        if ($grade == 5) return $this->andWhere(['>=', $field, TestTask::GRADE_5]);
        if ($grade == 4) return $this->andWhere(['<', $field, TestTask::GRADE_5])->andWhere(['>=', $field, TestTask::GRADE_4]);
        if ($grade == 3) return $this->andWhere(['<', $field, TestTask::GRADE_4])->andWhere(['>=', $field, TestTask::GRADE_3]);
        return $this->andWhere(['<', $field, TestTask::GRADE_3]);
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
     * Свой тест
     * @param $letterId int | array
     * @return $this
     */
    public function letter($letterId)
    {
        return $this->andWhere(['letter_id' => $letterId]);
    }

    /**
     * Список выученных слов
     * @param $letterId int | array
     * @return $this
     */
    public function learnedWords($letterId)
    {
        return $this
            ->own()
            ->finished()
            ->repetition(0)
            ->letter($letterId)
            ->innerJoin('test_task_question', 'test_task_question.test_task_id = test_task.id');
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