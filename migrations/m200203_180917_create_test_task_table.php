<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%test_task}}`.
 */
class m200203_180917_create_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull()->comment('Пользователь'),
            'status' => $this->tinyInteger()->notNull()->defaultExpression('0')->comment('Статус'),
            'created_at' => $this->timestamp()->notNull()->defaultValue(new Expression('CURRENT_TIMESTAMP'))->comment('Дата создания'),
        ]);

        $this->createIndex('user_id', self::TABLE_NAME, 'user_id');
        $this->addForeignKey(self::TABLE_NAME . '_user_FK', self::TABLE_NAME, 'user_id', 'user', 'id');

        $this->addCommentOnTable(self::TABLE_NAME, 'Список тестов');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
