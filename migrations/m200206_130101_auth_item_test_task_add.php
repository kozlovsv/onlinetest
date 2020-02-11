<?php

use app\components\RbacManager;
use yii\db\Migration;

class m200206_130101_auth_item_test_task_add extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
        [
            'name' => 'test_task.view',
            'description' => 'Мои тесты.Просмотр',
        ],
        [
            'name' => 'test_task.create',
            'description' => 'Мои тесты.Пройти тест',
        ],
        [
            'name' => 'test_task.delete',
            'description' => 'Мои тесты.Удаление',
        ],
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'test_task.view',
            'test_task.create',
            'test_task.delete',
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