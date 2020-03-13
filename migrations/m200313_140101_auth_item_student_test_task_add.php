<?php

use app\components\RbacManager;
use yii\db\Migration;

class m200313_140101_auth_item_student_test_task_add extends Migration
{
    /**
     * Новые разрешения для КРУД
     * @var array
     */
    private $permissions = [
        [
            'name' => 'student_test_task.view',
            'description' => 'Тесты учеников.Просмотр',
        ],
        [
            'name' => 'student_test_task.delete',
            'description' => 'Тесты учеников.Удаление',
        ],
    ];

    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'student_test_task.view',
            'student_test_task.delete',
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