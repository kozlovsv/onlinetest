<?php

use kozlovsv\crud\components\RbacManager;
use yii\db\Migration;

class m200129_160101_auth_item_user_add extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
        [
            'name' => 'user.view',
            'description' => 'Пользователи.Просмотр',
        ],
        [
            'name' => 'user.create',
            'description' => 'Пользователи.Создание',
        ],
        [
            'name' => 'user.update',
            'description' => 'Пользователи.Изменение',
        ],
        [
            'name' => 'user.delete',
            'description' => 'Пользователи.Удаление',
        ],
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
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