<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_task_question}}`.
 */
class m200203_181506_create_test_task_question_table extends Migration
{
    const TABLE_NAME = 'test_task_question';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'test_task_id' => $this->integer(11)->notNull()->comment('Test'),
            'vocabulary_word_id' => $this->integer(11)->notNull()->comment('Словарное слово'),
            'answer' => $this->string(255)->notNull()->defaultExpression('\'\'')->comment('Ответ'),
            'result' => $this->tinyInteger(1)->notNull()->defaultExpression('0')->comment('Результат'),
        ]);

        $this->createIndex('test_task_id', self::TABLE_NAME, 'test_task_id');
        $this->addForeignKey(self::TABLE_NAME . '_test_task_FK', self::TABLE_NAME, 'test_task_id', 'test_task', 'id', 'CASCADE');

        $this->createIndex('vocabulary_word_id', self::TABLE_NAME, 'vocabulary_word_id');
        $this->addForeignKey(self::TABLE_NAME . '_vocabulary_word_FK', self::TABLE_NAME, 'vocabulary_word_id', 'vocabulary_word', 'id');

        $this->addCommentOnTable(self::TABLE_NAME, 'Слова в тесте');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
