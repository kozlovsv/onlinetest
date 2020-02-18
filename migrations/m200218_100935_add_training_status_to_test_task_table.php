<?php

use yii\db\Migration;

/**
 * Class m200218_100935_add_training_status_to_test_task_table
 */
class m200218_100935_add_training_status_to_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'training_status', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Статус обучения'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'training_status');
    }
}
