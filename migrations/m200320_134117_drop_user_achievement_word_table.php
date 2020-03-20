<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%user_achievement_word}}`.
 */
class m200320_134117_drop_user_achievement_word_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('user_achievement_word');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
