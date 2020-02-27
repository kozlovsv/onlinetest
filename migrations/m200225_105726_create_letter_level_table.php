<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%letter_level}}`.
 */
class m200225_105726_create_letter_level_table extends Migration
{
    const TABLE_NAME = 'letter_level';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'letter_id' => $this->integer(11)->notNull()->comment('Буква'),
            'cnt_word_in_level' => $this->integer(11)->notNull()->comment('Количество слов в уровне'),
            'cnt_level' => $this->integer(11)->notNull()->comment('Количество уровней'),
        ]);

        $this->createIndex('letter_id', self::TABLE_NAME, 'letter_id', true);
        $this->addForeignKey(self::TABLE_NAME . '_letter_FK', self::TABLE_NAME, 'letter_id', 'letter', 'id');

        $this->addCommentOnTable(self::TABLE_NAME, 'Уровни достижений букв');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
