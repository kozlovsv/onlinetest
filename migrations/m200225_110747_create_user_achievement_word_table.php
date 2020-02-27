<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_letter_achievement_word}}`.
 */
class m200225_110747_create_user_achievement_word_table extends Migration
{
    const TABLE_NAME = 'user_achievement_word';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_achievement_id' => $this->integer(11)->notNull()->comment('Достижение пользователя'),
            'vocabulary_word_id' => $this->integer(11)->notNull()->comment('Выученное слово'),
        ]);

        $this->createIndex('user_achievement_id', self::TABLE_NAME, 'user_achievement_id');

        $this->addForeignKey(self::TABLE_NAME . '_user_achievement_FK', self::TABLE_NAME, 'user_achievement_id', 'user_achievement', 'id', 'CASCADE');

        $this->createIndex('vocabulary_word_id', self::TABLE_NAME, 'vocabulary_word_id');
        $this->addForeignKey(self::TABLE_NAME . '_vocabulary_word_FK', self::TABLE_NAME, 'vocabulary_word_id', 'vocabulary_word', 'id');

        $this->addCommentOnTable(self::TABLE_NAME, 'Пройденные слова');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
