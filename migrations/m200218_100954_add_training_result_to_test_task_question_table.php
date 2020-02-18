<?php

use yii\db\Migration;

/**
 * Class m200218_100954_add_training_result_to_test_task_question_table
 */
class m200218_100954_add_training_result_to_test_task_question_table extends Migration
{
    const TABLE_NAME = 'test_task_question';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'training_result', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Результат обучения'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'training_result');
    }
}
