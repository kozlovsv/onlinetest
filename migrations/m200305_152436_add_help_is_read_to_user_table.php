<?php

use yii\db\Migration;

/**
 * Class m200305_152436_add_help_is_read_to_user_table
 */
class m200305_152436_add_help_is_read_to_user_table extends Migration
{
    const TABLE_NAME = 'user';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'help_is_read', $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Справка прочитана?'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'help_is_read');
    }
}
