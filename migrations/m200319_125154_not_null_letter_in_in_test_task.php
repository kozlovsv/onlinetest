<?php

use yii\db\Migration;

/**
 * Class m200319_125154_not_null_letter_in_in_test_task
 */
class m200319_125154_not_null_letter_in_in_test_task extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->alterColumn(self::TABLE_NAME, 'letter_id', $this->integer(11)->NotNull()->comment('Буква'));
        $this->execute("SET foreign_key_checks = 1;");
    }

    public function safeDown()
    {
        $this->execute("SET foreign_key_checks = 0;");
        $this->alterColumn(self::TABLE_NAME, 'letter_id', $this->integer(11)->null()->comment('Буква'));
        $this->execute("SET foreign_key_checks = 1;");
    }
}
