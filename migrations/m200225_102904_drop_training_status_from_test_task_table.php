<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%training_status_from_test_task}}`.
 */
class m200225_102904_drop_training_status_from_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->dropColumn(self::TABLE_NAME, 'training_status');
    }

    public function safeDown()
    {
        $this->addColumn(self::TABLE_NAME, 'training_status', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Статус обучения'));
    }
}
