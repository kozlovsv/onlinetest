<?php

use app\components\RbacManager;
use yii\db\Migration;

class m200225_120101_auth_item_letter_level_add extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
        [
            'name' => 'letter_level.view',
            'description' => 'Уровни букв.Просмотр',
        ],
        [
            'name' => 'letter_level.create',
            'description' => 'Уровни букв.Создание',
        ],
        [
            'name' => 'letter_level.update',
            'description' => 'Уровни букв.Изменение',
        ],
        [
            'name' => 'letter_level.delete',
            'description' => 'Уровни букв.Удаление',
        ],
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'letter_level.view',
            'letter_level.create',
            'letter_level.update',
            'letter_level.delete',
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