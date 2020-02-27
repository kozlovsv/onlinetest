<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%training_result_to_test_task_question}}`.
 */
class m200225_105704_drop_training_result_to_test_task_question_table extends Migration
{
    const TABLE_NAME = 'test_task_question';

    public function safeUp()
    {
        $this->dropColumn(self::TABLE_NAME, 'training_result');
    }

    public function safeDown()
    {
        $this->addColumn(self::TABLE_NAME, 'training_result', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Результат обучения'));
    }
}
