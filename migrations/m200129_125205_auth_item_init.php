<?php /** @noinspection PhpIllegalPsrClassPathInspection */

use kozlovsv\crud\components\RbacManager;
use yii\db\Migration;

class m200129_125205_auth_item_init extends Migration
{
    /**
     * Разрешения для ролей
     * @var array
     */
    protected $child = [
        'administrator' => [
            'auth.manage',
            'log.view',
            'log.delete',
        ],
    ];
    /**
     * Роли
     * @var array
     */
    protected $roles = [
        [
            'name' => 'administrator',
            'description' => 'Администратор',
        ],
        [
            'name' => 'teacher',
            'description' => 'Учитель',
        ],
        [
            'name' => 'student',
            'description' => 'Ученик',
        ],
    ];
    private $permissions = [
        [
            'name' => 'auth.manage',
            'description' => 'Управление правами доступа',
        ],
        [
            'name' => 'log.view',
            'description' => 'Лог приложения.Просмотр',
        ],
        [
            'name' => 'log.delete',
            'description' => 'Лог приложения.Удаление',
        ],
    ];

    public function safeUp()
    {
        $manager = $this->getManager();
        $manager->up();
    }

    protected function getManager()
    {
        return new RbacManager([
            'permissions' => $this->permissions,
            'roles' => $this->roles,
            'child' => $this->child,
        ]);
    }

    public function safeDown()
    {
        $manager = $this->getManager();
        $manager->down();
    }
}