<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%letter}}`.
 */
class m200130_152024_create_letter_table extends Migration
{
    const TABLE_NAME = 'letter';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'title' => $this->string(1)->notNull()->unique()->comment('Буква'),
        ]);
        $this->addCommentOnTable(self::TABLE_NAME, 'Буквы');

        $letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'];
        foreach ($letters as $letter) {
            $this->insert(self::TABLE_NAME, ['title' => $letter]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
