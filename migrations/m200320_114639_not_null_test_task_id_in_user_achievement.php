<?php

use yii\db\Migration;

/**
 * Class m200320_114639_not_null_test_task_id_in_user_achievement
 */
class m200320_114639_not_null_test_task_id_in_user_achievement extends Migration
{
    const TABLE_NAME = 'user_achievement';

    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->alterColumn(self::TABLE_NAME, 'test_task_id', $this->integer(11)->NotNull()->comment('Тест'));
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->alterColumn(self::TABLE_NAME, 'test_task_id', $this->integer(11)->null()->comment('Тест'));
        $this->execute("SET foreign_key_checks = 1;");
    }
}
