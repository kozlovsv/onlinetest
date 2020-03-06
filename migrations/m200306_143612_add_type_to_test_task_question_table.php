<?php

use yii\db\Migration;

/**
 * Class m200306_143612_add_type_to_test_task_question_table
 */
class m200306_143612_add_type_to_test_task_question_table extends Migration
{
    const TABLE_NAME = 'test_task_question';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'type', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Тип'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'type');
    }
}
