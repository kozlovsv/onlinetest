<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_letter_achievement}}`.
 */
class m200225_110727_create_user_achievement_table extends Migration
{
    const TABLE_NAME = 'user_achievement';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull()->comment('Пользователь'),
            'letter_id' => $this->integer(11)->notNull()->comment('Буква'),
            'created_at' => $this->timestamp()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP'))->comment('Дата создания'),
        ]);

        $this->createIndex('user_id', self::TABLE_NAME, 'user_id');
        $this->addForeignKey(self::TABLE_NAME . '_user_FK', self::TABLE_NAME, 'user_id', 'user', 'id');

        $this->createIndex('letter_id', self::TABLE_NAME, 'letter_id');
        $this->addForeignKey(self::TABLE_NAME . '_letter_FK', self::TABLE_NAME, 'letter_id', 'letter', 'id');


        $this->addCommentOnTable(self::TABLE_NAME, 'Достижения пользователя по буквам');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
