<?php

use app\models\TestTask;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m200228_131742_add_rating_to_test_task_table
 */
class m200228_131742_add_rating_to_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'rating', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('% правильных заданий'));
        $this->update(self::TABLE_NAME, ['rating' => new Expression('TRUNCATE(((SELECT COUNT(*) FROM test_task_question WHERE test_task_question.test_task_id = test_task.id AND result = 1) / (SELECT COUNT(*) FROM test_task_question WHERE test_task_question.test_task_id = test_task.id)) * 100, 0)')], ['status' => TestTask::STATUS_FINISHED]);
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'rating');
    }
}
