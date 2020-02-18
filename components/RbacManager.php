<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Компонента для добавления прав через миграции
 */
class RbacManager extends Component
{
    /**
     * Роли
     * @var array
     */
    public $roles = [];

    /**
     * Права
     * @var array
     */
    public $permissions = [];

    /**
     * Права для удаления
     * @var array
     */
    public $permissionsRemove = [];

    /**
     * Разрешения для ролей
     * @var array
     */
    public $child = [];

    /**
     * Менеджер
     * @var AuthManager
     */
    protected $authManager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->authManager = Yii::$app->authManager;
    }

    /**
     * Применить
     */
    public function up()
    {
        $this->addRoles($this->roles);
        $this->addPermissions($this->permissions);
        $this->addChild($this->child);
        $this->removePermissions($this->permissionsRemove);
    }

    /**
     * Откатить
     */
    public function down()
    {
        $this->addPermissions($this->permissionsRemove);
        $this->removeRoles($this->roles);
        $this->removePermissions($this->permissions);
    }

    /**
     * Добавить роли
     * @param array $roles
     */
    protected function addRoles($roles)
    {
        foreach ($roles as $item) {
            $existItem = $this->authManager->getRole($item['name']);
            if ($existItem === null) {
                $role = $this->authManager->createRole($item['name']);
                $role->description = $item['description'];
                $this->authManager->add($role);
            }
        }
    }

    /**
     * Добавить права
     * @param array $permissions
     */
    protected function addPermissions($permissions)
    {
        foreach ($permissions as $item) {
            $existItem = $this->authManager->getPermission($item['name']);
            if ($existItem === null) {
                $permission = $this->authManager->createPermission($item['name']);
                $permission->description = $item['description'];
                $this->authManager->add($permission);
            }
        }
    }

    /**
     * Присвоить права ролям
     * @param array $child
     */
    protected function addChild($child)
    {
        foreach ($child as $roleName => $permissionsNames) {
            $role = $this->authManager->getRole($roleName);
            foreach ($permissionsNames as $permissionName) {
                $permission = $this->authManager->getPermission($permissionName);
                if (!$this->authManager->hasChild($role, $permission)) {
                    $this->authManager->addChild($role, $permission);
                }
            }
        }
    }

    /**
     * Очистить роли
     * @param array $roles
     */
    protected function removeRoles($roles)
    {
        foreach ($roles as $item) {
            $existItem = $this->authManager->getRole($item['name']);
            if ($existItem) {
                $this->authManager->remove($existItem);
            }
        }
    }

    /**
     * Очистить права
     * @param array $permissions
     */
    protected function removePermissions($permissions)
    {
        foreach ($permissions as $item) {
            $existItem = $this->authManager->getPermission($item['name']);
            if ($existItem) {
                $this->authManager->remove($existItem);
            }
        }
    }

}