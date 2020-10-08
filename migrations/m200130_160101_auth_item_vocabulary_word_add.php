<?php

use kozlovsv\crud\components\RbacManager;
use yii\db\Migration;

class m200130_160101_auth_item_vocabulary_word_add extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
        [
            'name' => 'vocabulary_word.view',
            'description' => 'Словарные слова.Просмотр',
        ],
        [
            'name' => 'vocabulary_word.create',
            'description' => 'Словарные слова.Создание',
        ],
        [
            'name' => 'vocabulary_word.update',
            'description' => 'Словарные слова.Изменение',
        ],
        [
            'name' => 'vocabulary_word.delete',
            'description' => 'Словарные слова.Удаление',
        ],
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'vocabulary_word.view',
            'vocabulary_word.create',
            'vocabulary_word.update',
            'vocabulary_word.delete',
        ],
    ];

    public function safeUp()
    {
        $manager = $this->getManager();
        $manager->up();
        Yii::$app->cache->flush();
    }

    protected function getManager()
    {
        return new RbacManager([
            'permissions' => $this->permissions,
            'child' => $this->child,
        ]);
    }

    public function safeDown()
    {
        $manager = $this->getManager();
        $manager->down();
        Yii::$app->cache->flush();
    }
}