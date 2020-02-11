<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vocabulary_words}}`.
 */
class m200130_153840_create_vocabulary_word_table extends Migration
{
    const TABLE_NAME = 'vocabulary_word';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull()->unique()->comment('Слово'),
            'letter_id' => $this->integer(11)->notNull()->comment('Буква'),
        ]);

        $this->createIndex('letter_id', self::TABLE_NAME, 'letter_id');
        $this->addForeignKey(self::TABLE_NAME . '_letter_FK', self::TABLE_NAME, 'letter_id', 'letter', 'id');

        $this->addCommentOnTable(self::TABLE_NAME, 'Словарные слова');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
