<?php

use yii\db\Migration;

/**
 * Class m200213_135311_add_passed_ad_test_task_table
 */
class m200213_135311_add_passed_ad_test_task_table extends Migration
{
    const TABLE_NAME = 'test_task';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'passed_at', $this->timestamp()->null()->comment('Дата прохождения'));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'passed_at');
    }
}
