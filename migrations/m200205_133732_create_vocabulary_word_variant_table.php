<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vocabulary_word_variant}}`.
 */
class m200205_133732_create_vocabulary_word_variant_table extends Migration
{
    const TABLE_NAME = 'vocabulary_word_variant';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'vocabulary_word_id' => $this->integer(11)->notNull()->comment('Словарное слово'),
            'title' => $this->string(255)->notNull()->defaultExpression('\'\'')->comment('Неправильное слово'),
        ]);

        $this->createIndex('vocabulary_word_id', self::TABLE_NAME, 'vocabulary_word_id');
        $this->addForeignKey(self::TABLE_NAME . '_vocabulary_word_FK', self::TABLE_NAME, 'vocabulary_word_id', 'vocabulary_word', 'id', 'CASCADE');

        $this->addCommentOnTable(self::TABLE_NAME, 'Неправильные варианты слов');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
