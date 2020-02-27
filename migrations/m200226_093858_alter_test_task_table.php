<?php

use yii\db\Migration;

/**
 * Class m200226_093858_alter_test_task_table
 */
class m200226_093858_alter_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'is_repetition', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Тест повторение?'));
        $this->addColumn(self::TABLE_NAME, 'letter_id', $this->integer(11)->null()->comment('Буква'));

        $this->createIndex('letter_id', self::TABLE_NAME, 'letter_id');
        $this->addForeignKey(self::TABLE_NAME . '_letter_FK', self::TABLE_NAME, 'letter_id', 'letter', 'id');
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'is_repetition');
        $this->dropForeignKey(self::TABLE_NAME . '_letter_FK', self::TABLE_NAME);
        $this->dropIndex('letter_id', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, 'letter_id');
    }
}
