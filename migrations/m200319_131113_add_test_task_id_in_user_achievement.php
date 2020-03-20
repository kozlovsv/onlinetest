<?php

use yii\db\Migration;

/**
 * Class m200319_131113_add_test_task_id_in_user_achievement
 */
class m200319_131113_add_test_task_id_in_user_achievement extends Migration
{
    const TABLE_NAME = 'user_achievement';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'test_task_id', $this->integer(11)->null()->comment('Тест'));
        $this->createIndex('test_task_id', self::TABLE_NAME, 'test_task_id');
        $this->addForeignKey(self::TABLE_NAME . '_test_task_FK', self::TABLE_NAME, 'test_task_id', 'test_task', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey(self::TABLE_NAME . '_test_task_FK', self::TABLE_NAME);
        $this->dropIndex('test_task_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'test_task_id');
    }
}
